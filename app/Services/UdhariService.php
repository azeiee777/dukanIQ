<?php

namespace App\Services;

use App\Interfaces\PartyRepositoryInterface;
use App\Models\Party;
use App\Models\UdhariEntry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UdhariService
{
    protected $repository;

    public function __construct(PartyRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function listParties()
    {
        return $this->repository->getAllByUser(Auth::id());
    }

    public function findParty(int $id): Party
    {
        return $this->repository->find($id, Auth::id());
    }

    public function createParty(array $data): Party
    {
        $data['user_id'] = Auth::id();

        return $this->repository->create($data);
    }

    public function updateParty(int $id, array $data): Party
    {
        return $this->repository->update($id, $data, Auth::id());
    }

    public function deleteParty(int $id): void
    {
        $this->repository->delete($id, Auth::id());
    }

    public function addEntry(int $partyId, string $type, float $amount, string $date, ?string $note = null): UdhariEntry
    {
        if ($amount <= 0) {
            throw ValidationException::withMessages(['amount' => 'Amount must be greater than zero.']);
        }

        // Ownership check — throws if the party doesn't belong to the current user.
        $this->findParty($partyId);

        return UdhariEntry::create([
            'user_id' => Auth::id(),
            'party_id' => $partyId,
            'type' => $type,
            'amount' => $amount,
            'note' => $note,
            'date' => $date,
        ]);
    }

    public function deleteEntry(int $entryId): void
    {
        UdhariEntry::where('id', $entryId)->where('user_id', Auth::id())->delete();
    }

    /**
     * Ledger for one party: entries oldest-first with a running balance,
     * matching how a khata book reads.
     */
    public function ledgerFor(Party $party): array
    {
        $running = 0.0;
        $rows = [];

        foreach ($party->udhariEntries->sortBy(['date', 'created_at']) as $entry) {
            $running += $entry->signedAmount();
            $rows[] = [
                'entry' => $entry,
                'running_balance' => $running,
            ];
        }

        return array_reverse($rows);
    }

    /**
     * Total Receivables (sum of positive party balances) and Total Payables
     * (sum of absolute negative party balances) — never netted against each
     * other here, so both figures stay honest on their own.
     */
    public function totals(): array
    {
        $receivable = 0.0;
        $payable = 0.0;

        foreach ($this->listParties() as $party) {
            $balance = $party->balance();

            if ($balance > 0) {
                $receivable += $balance;
            } elseif ($balance < 0) {
                $payable += abs($balance);
            }
        }

        return [
            'receivable' => $receivable,
            'payable' => $payable,
            'net' => $receivable - $payable,
        ];
    }
}

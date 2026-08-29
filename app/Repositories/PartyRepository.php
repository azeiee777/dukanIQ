<?php

namespace App\Repositories;

use App\Interfaces\PartyRepositoryInterface;
use App\Models\Party;

class PartyRepository implements PartyRepositoryInterface
{
    public function getAllByUser(int $userId)
    {
        return Party::where('user_id', $userId)
            ->with('udhariEntries')
            ->orderBy('name')
            ->get();
    }

    public function find(int $id, int $userId)
    {
        return Party::where('id', $id)->where('user_id', $userId)->with('udhariEntries')->firstOrFail();
    }

    public function create(array $data)
    {
        return Party::create($data);
    }

    public function update(int $id, array $data, int $userId)
    {
        $party = Party::where('id', $id)->where('user_id', $userId)->firstOrFail();
        $party->update($data);

        return $party->refresh();
    }

    public function delete(int $id, int $userId)
    {
        return Party::where('id', $id)->where('user_id', $userId)->delete();
    }
}

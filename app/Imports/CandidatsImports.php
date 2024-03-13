<?php

namespace App\Imports;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use App\Models\Candidats;

class CandidatsImports implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function __construct($type,$pays,$idElection)
    {
        $this->idElection = $idElection;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $picture = $row['picture'] ?? 'https://example.com/default-picture.jpg'; // Provide a default picture URL or NULL if the 'picture' field is not present
            // $picture = $row['picture'] === NULL ? NULL : $picture; // If 'photos' is NULL, set 'picture' to NULL

            Candidats::create([
                'fullName' => $row['Full Name'] ?? "NOM", // Assuming the column header is 'Full Name'
                'picture' => $picture,
                'election_id'=>$this->idElection
           ]);
        }
    }

}

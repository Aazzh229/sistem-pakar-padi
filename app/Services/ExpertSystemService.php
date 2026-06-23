<?php

namespace App\Services;
use App\Models\Rule;

class ExpertSystemService
{
    public function calculateCF(
        float $ruleCF,
        array $evidenceValues
    ): float
    {
        $minCF = min($evidenceValues);

        return round(
            $ruleCF * $minCF,
            4
        );
    }


public function combineCF(
    float $cf1,
    float $cf2
): float
{
    return round(
        $cf1 + $cf2 * (1 - $cf1),
        4
    );
}

public function diagnose(array $userFacts)
{
    $hasil = [];

    $rules = Rule::with([
        'diagnosa',
        'details'
    ])->get();

    foreach ($rules as $rule) {

        $totalGejala =
            $rule->details->count();

        $jumlahCocok = 0;

        $evidenceValues = [];

        foreach ($rule->details as $detail) {

            $kodeGejala =
                $detail->kode_gejala;

            if (
                isset(
                    $userFacts[$kodeGejala]
                )
            ) {

                $jumlahCocok++;

                $evidenceValues[] =
                    $userFacts[$kodeGejala];
            }
        }

        if ($jumlahCocok == 0) {
            continue;
        }

        $persentaseCocok =
            $jumlahCocok /
            $totalGejala;

        $cfAwal =
            $this->calculateCF(
                $rule->cf_rule,
                $evidenceValues
            );

        $cfAkhir =
            round(
                $cfAwal *
                $persentaseCocok,
                4
            );

        $hasil[] = [

            'kode_rule' =>
                $rule->kode_rule,

            'kode_diagnosa' =>
                $rule->kode_diagnosa,

            'diagnosa' =>
                $rule->diagnosa->nama_diagnosa,

            'cf' =>
                $cfAkhir,

            'persentase_cocok' =>
                round(
                    $persentaseCocok * 100,
                    2
                )
        ];
    }

    usort(
        $hasil,
        fn($a, $b)
        => $b['cf']
        <=> $a['cf']
    );

    return $hasil;
}
public function getNextSymptoms(
    array $selectedSymptoms
)
{
    $nextSymptoms = [];

    $rules = Rule::with('details.gejala')
        ->get();

    foreach ($rules as $rule) {

        $ruleSymptoms =
            $rule->details
                ->pluck('kode_gejala')
                ->toArray();

        $matched =
            array_intersect(
                $selectedSymptoms,
                $ruleSymptoms
            );

        if (count($matched) > 0) {

            foreach (
                $rule->details
                as $detail
            ) {

                if (
                    !in_array(
                        $detail->kode_gejala,
                        $selectedSymptoms
                    )
                ) {

                    $nextSymptoms[
                        $detail->kode_gejala
                    ] =
                        $detail
                        ->gejala
                        ->nama_gejala;
                }
            }
        }
    }

    return $nextSymptoms;
}

}
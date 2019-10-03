<?php
namespace genes\infrastructure\repository;

use genes\models\Gene;
use genes\models\GeneQuery;
use yii\web\NotFoundHttpException;

class GeneRepository implements GeneRepositoryInterface
{
    /** @inheritDoc */
    public function getGene($geneId): array
    {
        $geneArray = (new GeneQuery(Gene::class))
            ->select('*')
            ->where(['ID' => $geneId])
            ->asArray()
            ->one();
        if(!$geneArray) {
            throw new NotFoundHttpException("Gene {$geneId} not found");
        }
        return $geneArray;
    }
}
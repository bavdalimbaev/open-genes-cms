<?php

namespace app\models;

use app\models\behaviors\ChangelogBehavior;
use app\models\traits\RuEnActiveRecordTrait;
use Yii;

/**
 * This is the model class for table "orthologs".
 *
 * @property int $id
 * @property string|null $symbol
 * @property int|null $model_organism_id
 *
 * @property GeneToOrthologs[] $geneToOrthologs
 * @property ModelOrganism $organism
 */
class Orthologs extends common\Orthologs
{
    use RuEnActiveRecordTrait;

    public $name;

    /**
    * {@inheritdoc}
    */
    public function behaviors()
    {
        return [
            ChangelogBehavior::class
        ];
    }

    /**
    * {@inheritdoc}
    */
    public function attributeLabels()
    {
        return [
                    'id' => 'ID',
                    'symbol' => 'Symbol',
                    'model_organism_id' => 'Organism ID',
                ];
    }


    /**
    * Gets query for [[GeneToOrthologs]].
    *
    * @return \yii\db\ActiveQuery|\app\models\common\GeneToOrthologsQuery
    */
    public function getGeneToOrthologs()
    {
    return $this->hasMany(GeneToOrthologs::class, ['ortholog_id' => 'id']);
    }

    /**
    * Gets query for [[Organism]].
    *
    * @return \yii\db\ActiveQuery|\app\models\common\ModelOrganismQuery
    */
    public function getOrganism()
    {
    return $this->hasOne(ModelOrganism::class, ['id' => 'model_organism_id']);
    }

    public function getLinkedGenesIds()
    {
        return []; // todo implement for column with related genes
    }

}

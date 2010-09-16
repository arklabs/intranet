<?php


class PhraseologyTable extends myDoctrineTable
{
	public function like($pattern, Doctrine_Query $q = null){
        if (is_null($q))
        {
            $q = Doctrine_Query::create()
            ->from('Phraseology ph');
        }
        $alias = $q->getRootAlias();

        $q->addWhere(sprintf('%s.title LIKE "%s" OR %s.content LIKE "%s"', $alias, "%".$pattern."%", $alias, "%".$pattern."%"))
		  ->addWhere($alias.'.is_active = 1')
		  ->orderBy($alias.'.pos_rank DESC, '.$alias.'.neg_rank  DESC');

        return $q;
    }
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Phraseology');
    }
}
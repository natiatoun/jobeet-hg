<?php

class JobeetJobTable extends Doctrine_Table {

    static public $types = array(
        'full-time' => 'Full Time',
        'part-time' => 'Part Time',
        'freelance' => 'Freelance'
    );

    public function getTypes() {
        return self::$types;
    }

    public static function getInstance() {
        return Doctrine_Core::getTable('JobeetJob');
    }

    public function retrieveActiveJob(Doctrine_Query $q) {
        return $this->addActiveJobsQuery($q)->fetchOne();
    }

    public function getActiveJobs(Doctrine_Query $q = null) {
        return $this->addActiveJobsQuery($q)->execute();
    }

    public function countActiveJobs(Doctrine_Query $q = null) {
        return $this->addACtiveJobsQuery($q)->count();
    }

    public function addActiveJobsQuery($q = null) {
        if (is_null($q)) {
            $q = Doctrine_Query::create()
                            ->from('JobeetJob j');
        }

        $alias = $q->getRootAlias();

        $q->andWhere($alias . '.expires_at > ?', date('Y-m-d H:i:s', time()))
                ->addOrderBy($alias . '.created_at DESC')
                ->andWhere($alias . '.is_activated = ?', 1);

        return $q;
    }

    public function cleanup($days) {
        $q = $this->createQuery('a')
                        ->delete()
                        ->andWhere('a.is_activated = ?', 0)
                        ->andWhere('a.created_at < ?', date('Y-m-d', time() - 86400 * $days));

        return $q->execute();
    }

}
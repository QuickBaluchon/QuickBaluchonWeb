<?php

class RoadmapManager extends Model{

    public function getRoadmaps($fields, $inner = NULL, $date = NULL, $client = NULL) {
        if($inner != NULL)
            return $this->getCollection('roadmap', ["fields" => join(',',$fields), "inner" => join(',',$inner), "date" => $date, "client" => $client]);
        else
            return $this->getCollection('roadmap', ["fields" => join(',',$fields), "client" => $client, "date" => $date]);
    }

    public function getRoadmap($id ,$fields, $inner = NULL, $date = NULL) {
        if($inner != NULL)
            return $this->getCollection('roadmap',$id , ["fields" => join(',',$fields), "inner" => join(',',$inner), "date" => $date]);
        else
            return $this->getRessource('roadmap', $id , ["fields" => join(',',$fields)]);
    }
}

<?php

class RoadmapManager extends Model{

    public function getRoadmaps(array $fields, array $inner = NULL, string $date = NULL, int $deliveryman = NULL) {
        if($inner != NULL)
            return $this->getCollection('roadmap', ["fields" => join(',',$fields), "inner" => join(',',$inner), "date" => $date, "deliveryman" => $deliveryman]);
        else
            return $this->getCollection('roadmap', ["fields" => join(',',$fields), "deliveryman" => $deliveryman, "date" => $date]);
    }

    public function getRoadmap($id, $fields, $inner = NULL, $date = NULL) {
        if($inner != NULL)
            return $this->getCollection('roadmap',$id , ["fields" => join(',',$fields), "inner" => join(',',$inner), "date" => $date]);
        else
            return $this->getRessource('roadmap', $id , ["date" => $date, 'deliveryman' => $_SESSION['id']]);
    }
}

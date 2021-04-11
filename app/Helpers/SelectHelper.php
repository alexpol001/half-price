<?php
namespace App\Helpers;

use App\Models\UwtModel;

class SelectHelper {
    public static function getSelectItems($data, $exclude = []) {
        return $data;
    }
}
//function getSelectItems($json, $field = null)
//{
//    $json = json_decode($json);
//    $result = [];
//    if ($items = $json->items) {
//        $result[$field ? $field->title : ''] = $items;
//    }
//    if ($groups = $json->groups) {
//        foreach ($groups as $group_id) {
//            $items = [];
//            $title = null;
//            $group = Group::findOne($group_id);
//            if ($group && !$group->isCluster()) {
//                foreach ($group->materials as $material) {
//                    $items['m_' . $material->id] = $material->title;
//                }
//                if ($items) {
//                    $title = $group->title;
//                    $result[$title] = $result[$title] ? array_merge($result[$title], $items) : $items;
//                }
//            } else {
//                $groups = Group::findAll(['group_id' => $group_id]);
//                foreach ($groups as $group) {
//                    if ($group->isMaterials() || $group->isSingleton()) {
//                        $items['g_' . $group->id] = $group->title;
//                    }
//                }
//                if ($items) {
//                    $title = $field ? $field->title : '';
//                    $result[$title] = $result[$title] ? array_merge($result[$title], $items) : $items;
//                }
//                if ($group_id === 0) {
//                    $clusters = Group::getClusters();
//                    foreach ($clusters as $cluster) {
//                        $items = [];
//                        foreach ($cluster->groups as $group) {
//                            if ($group->isMaterials() || $group->isSingleton()) {
//                                $items['g_' . $group->id] = $group->title;
//                            }
//                        }
//                        if ($items) {
//                            $title = $cluster->title;
//                            $result[$title] = $result[$title] ? array_merge($result[$title], $items) : $items;
//                        }
//                    }
//                }
//            }
//        }
//    }
//    return $result;
//}

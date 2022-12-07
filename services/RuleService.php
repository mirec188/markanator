<?php 

class RuleService {

    public function runAllRules($row) {

    }

    public function ruleByContainText($row, $fields, $containString) {
        $result = false;

        if ($this->contains($containString, $row-["additional_info"]))

        return $result;
    }

    private function contains($needle, $haystack) {
        return strpos($haystack, $needle) !== false;
    }

}
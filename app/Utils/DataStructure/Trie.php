<?php

namespace App\Utils\DataStructure;

class Trie {
    private $root;

    public function __construct() {
        $this->root = new TrieNode();
    }

    public function insert($word) {
        $node = $this->root;
        $length = mb_strlen($word);
        for ($i = 0; $i < $length; $i++) {
            $char = mb_substr($word, $i, 1);
            if (!isset($node->children[$char])) {
                $node->children[$char] = new TrieNode();
            }
            $node = $node->children[$char];
        }
        $node->isEndOfWord = true;
    }

    public function search($text) {
        $node = $this->root;
        $length = mb_strlen($text);
        for ($i = 0; $i < $length; $i++) {
            $char = mb_substr($text, $i, 1);
            if (!isset($node->children[$char])) {
                return false;
            }
            $node = $node->children[$char];
            if ($node->isEndOfWord) {
                return true;
            }
        }
        return false;
    }
}


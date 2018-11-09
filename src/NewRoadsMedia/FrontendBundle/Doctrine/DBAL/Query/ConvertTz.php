<?php

namespace NewRoadsMedia\FrontendBundle\Doctrine\DBAL\Query;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;

class ConvertTz extends FunctionNode
{
    public $dt;
    public $fromTz;
    public $toTz;

    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->dt = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->fromTz = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->toTz = $parser->StringPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return sprintf('CONVERT_TZ(%s, %s, %s)'
            , $this->dt->dispatch($sqlWalker)
            , $sqlWalker->walkStringPrimary($this->fromTz)
            , $sqlWalker->walkStringPrimary($this->toTz)
        );
    }
}
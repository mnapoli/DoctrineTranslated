<?php

namespace Mnapoli\Translated\Doctrine;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\PathExpression;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * Walks the DQL AST.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class TranslatedFunction extends FunctionNode
{
    /**
     * @var PathExpression
     */
    private $field;

    /**
     * @param Parser $parser
     *
     * @return void
     */
    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $parser->match(Lexer::T_IDENTIFIER);
        $identificationVariable = $parser->getLexer()->token['value'];

        $parser->match(Lexer::T_DOT);

        $parser->match(Lexer::T_IDENTIFIER);
        $field = $parser->getLexer()->token['value'];

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);

        $this->field = new PathExpression(PathExpression::TYPE_STATE_FIELD, $identificationVariable, $field);
        $this->field->type = PathExpression::TYPE_STATE_FIELD;

        // TODO how to pass the locale up to here?
        // https://github.com/doctrine/doctrine2/pull/991
        $this->field->field .= '.en';
    }

    /**
     * @param SqlWalker $sqlWalker
     *
     * @return string
     */
    public function getSql(SqlWalker $sqlWalker)
    {
        return $this->field->dispatch($sqlWalker);
    }
}

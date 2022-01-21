<?php

namespace App\Filter;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Core\Exception\InvalidArgumentException;
use Doctrine\ORM\QueryBuilder;

class FullTextSearchFilter extends SearchFilter
{
    private const PROPERTY_NAME = 'search';

    /**
     * {@inheritdoc}
     */
    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        // This filter will work with the 'search'-query-parameter only.
        if ($property !== self::PROPERTY_NAME) {
            return;
        }
        
        $orExpressions = [];

        // Split the $value at spaces.
        // For each term 'or' all given properties by strategy.
        // 'And' all 'or'-parts.
        $terms = explode(" ", $value);
        foreach ($terms as $index => $term) {
            foreach ($this->properties as $property => $strategy) {
                $strategy = $strategy ?? self::STRATEGY_EXACT;
                $alias = $queryBuilder->getRootAliases()[0];
                $field = $property;

                $associations = [];
                if ($this->isPropertyNested($property, $resourceClass)) {
                    [$alias, $field, $associations] = $this->addJoinsForNestedProperty($property, $alias, $queryBuilder, $queryNameGenerator, $resourceClass);
                }

                $caseSensitive = true;
                $metadata = $this->getNestedMetadata($resourceClass, $associations);

                if ($metadata->hasField($field)) {
                    if ('id' === $field) {
                        $term = $this->getIdFromValue($term);
                    }

                    if (!$this->hasValidValues((array)$term, $this->getDoctrineFieldType($property, $resourceClass))) {
                        $this->logger->notice('Invalid filter ignored', [
                            'exception' => new InvalidArgumentException(sprintf('Values for field "%s" are not valid according to the doctrine type.', $field)),
                        ]);
                        continue;
                    }

                    // prefixing the strategy with i makes it case insensitive
                    if (0 === strpos($strategy, 'i')) {
                        $strategy = substr($strategy, 1);
                        $caseSensitive = false;
                    }

                    $orExpressions[$index][] = $this->addWhereByStrategy($strategy, $queryBuilder, $queryNameGenerator, $alias, $field, $term, $caseSensitive);
                }
            }
        }

        $exprBuilder = $queryBuilder->expr();
        foreach ($orExpressions as $expr) {
            $queryBuilder->andWhere($exprBuilder->orX(...$expr));
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function addWhereByStrategy(string $strategy, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $alias, string $field, $value, bool $caseSensitive)
    {
        $wrapCase = $this->createWrapCase($caseSensitive);
        $valueParameter = $queryNameGenerator->generateParameterName($field);
        $exprBuilder = $queryBuilder->expr();

        $queryBuilder->setParameter($valueParameter, $value);
        switch ($strategy) {
            case null:
            case self::STRATEGY_EXACT:
                return $exprBuilder->eq($wrapCase("$alias.$field"), $wrapCase(":$valueParameter"));
            case self::STRATEGY_PARTIAL:
                return $exprBuilder->like($wrapCase("$alias.$field"), $exprBuilder->concat("'%'", $wrapCase(":$valueParameter"), "'%'"));
            case self::STRATEGY_START:
                return $exprBuilder->like($wrapCase("$alias.$field"), $exprBuilder->concat($wrapCase(":$valueParameter"), "'%'"));
            case self::STRATEGY_END:
                return $exprBuilder->like($wrapCase("$alias.$field"), $exprBuilder->concat("'%'", $wrapCase(":$valueParameter")));
            case self::STRATEGY_WORD_START:
                return $exprBuilder->orX(
                    $exprBuilder->like($wrapCase("$alias.$field"), $exprBuilder->concat($wrapCase(":$valueParameter"), "'%'")),
                    $exprBuilder->like($wrapCase("$alias.$field"), $exprBuilder->concat("'%'", $wrapCase(":$valueParameter")))
                );
            default:
                throw new InvalidArgumentException(sprintf('strategy %s does not exist.', $strategy));
        }
    }
}

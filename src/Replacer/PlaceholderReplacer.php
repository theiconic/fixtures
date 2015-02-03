<?php

namespace TheIconic\Fixtures\Replacer;

use TheIconic\Fixtures\Fixture\Fixture;

/**
 * Class PlaceholderReplacer
 * @package TheIconic\Fixtures\Replacer
 */
class PlaceholderReplacer implements ReplacerInterface
{
    /**
     * {@inheritDoc}
     */
    public function replaceValues(Fixture $fixture, array $replacementPlaceholders)
    {
        // If no replacement will take place for this fixture, return right away
        if (!array_key_exists($fixture->getName(), $replacementPlaceholders)) {
            return $fixture;
        } else {
            $replacementPlaceholders = $replacementPlaceholders[$fixture->getName()];
        }

        $replacedData = [];

        foreach ($fixture as $index => $fixtureData) {
            foreach ($fixtureData as $column => $value) {
                foreach ($replacementPlaceholders as $placeholder => $newValue) {
                    if (
                        strpos($placeholder, self::REPLACEMENT_PLACEHOLDER_PREFIX) !== false
                        && $placeholder === $value
                    ) {
                        $replacedData[$index][$column] = $newValue;
                    } else {
                        $replacedData[$index][$column] = $value;
                    }
                }
            }
        }

        return $fixture->setData($replacedData);
    }
}

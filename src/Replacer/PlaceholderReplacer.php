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
        // Only attempt to replace values when fixture is marked for it
        if (array_key_exists($fixture->getName(), $replacementPlaceholders)) {
            $replacementPlaceholders = $replacementPlaceholders[$fixture->getName()];
            $replacedData = $this->replacePlaceholders($fixture, $replacementPlaceholders);
            $fixture->setData($replacedData);
        }

        return $fixture;
    }

    /**
     * Regenerates all data inside the fixture replacing placeholders when necessary.
     *
     * @param Fixture $fixture
     * @param array $replacementPlaceholders
     * @return array
     */
    private function replacePlaceholders(Fixture $fixture, array $replacementPlaceholders)
    {
        $replacedData = [];

        foreach ($fixture as $index => $fixtureData) {
            foreach ($fixtureData as $column => $value) {
                if (in_array($value, array_keys($replacementPlaceholders), true) &&
                    strpos(key($replacementPlaceholders), self::REPLACEMENT_PLACEHOLDER_PREFIX) !== false) {
                    $replacedData[$index][$column] = $replacementPlaceholders[$value];
                } else {
                    $replacedData[$index][$column] = $value;
                }
            }
        }
        return $replacedData;
    }
}

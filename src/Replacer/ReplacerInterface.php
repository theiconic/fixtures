<?php


namespace TheIconic\Fixtures\Replacer;

use TheIconic\Fixtures\Fixture\Fixture;

/**
 * Interface ReplacerInterface
 * @package TheIconic\Fixtures\Replacer
 */
interface ReplacerInterface
{
    /**
     * Valid prefix for a placeholder replacement.
     */
    const REPLACEMENT_PLACEHOLDER_PREFIX = 'fx:placeholder:';

    /**
     * Replaces all the dynamic and placeholder values in the fixture.
     *
     * @param Fixture $fixture
     * @param array $replacementPlaceholders
     * @return Fixture
     */
    public function replaceValues(Fixture $fixture, array $replacementPlaceholders);
}

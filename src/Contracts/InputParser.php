<?php 

namespace Olymbytes\H3pha3stus\Contracts;

interface InputParser
{
    /**
     * Parse the input
     *
     * @param  $input
     * @return array
     */
    public function parse(array $input);
}
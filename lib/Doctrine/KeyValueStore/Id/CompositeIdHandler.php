<?php

/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <http://www.doctrine-project.org>.
 */

namespace Doctrine\KeyValueStore\Id;

use Doctrine\KeyValueStore\Mapping\ClassMetadata;
use InvalidArgumentException;

class CompositeIdHandler implements IdHandlingStrategy
{
    /**
     * {@inheritDoc}
     */
    public function normalizeId(ClassMetadata $metadata, $key)
    {
        if (! $metadata->isCompositeKey && ! is_array($key)) {
            return [
                $metadata->identifier[0] => $key,
            ];
        }

        if (! is_array($key)) {
            throw new InvalidArgumentException('Array of identifier key-value pairs is expected!');
        }

        $id = [];

        foreach ($metadata->identifier as $field) {
            if (! isset($key[$field])) {
                throw new InvalidArgumentException(
                    sprintf('Missing identifier field %s in request for the primary key.', $field)
                );
            }
            $id[$field] = $key[$field];
        }

        return $id;
    }

    /**
     * {@inheritDoc}
     */
    public function getIdentifier(ClassMetadata $metadata, $object)
    {
        return $metadata->getIdentifierValues($object);
    }

    /**
     * {@inheritDoc}
     */
    public function hash($key)
    {
        return implode('__##__', (array) $key);
    }
}

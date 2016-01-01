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

namespace Doctrine\KeyValueStore\Mapping;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Persistence\Mapping\ClassMetadata as CommonClassMetadata;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriver;
use Doctrine\KeyValueStore\Mapping\Annotations\Entity;
use Doctrine\KeyValueStore\Mapping\Annotations\Id;
use Doctrine\KeyValueStore\Mapping\Annotations\Transient;
use ReflectionClass;

class AnnotationDriver implements MappingDriver
{
    /**
     * Doctrine common annotations reader.
     *
     * @var AnnotationReader
     */
    private $reader;

    /**
     * Constructor with required dependencies.
     *
     * @param $reader AnnotationReader Doctrine common annotations reader.
     */
    public function __construct(AnnotationReader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * Loads the metadata for the specified class into the provided container.
     *
     * @param string              $className
     * @param CommonClassMetadata $metadata
     */
    public function loadMetadataForClass($className, CommonClassMetadata $metadata)
    {
        $class = $metadata->getReflectionClass();
        if (! $class) {
            // this happens when running annotation driver in combination with
            // static reflection services. This is not the nicest fix
            $class = new ReflectionClass($metadata->name);
        }

        $entityAnnot = $this->reader->getClassAnnotation($class, Entity::class);
        if (! $entityAnnot) {
            throw new \InvalidArgumentException($metadata->name . ' is not a valid key-value-store entity.');
        }
        $metadata->storageName = $entityAnnot->storageName;

        // Evaluate annotations on properties/fields
        foreach ($class->getProperties() as $property) {
            $idAnnot = $this->reader->getPropertyAnnotation($property, Id::class);
            if ($idAnnot) {
                $metadata->mapIdentifier($property->getName());

                // if it's an identifier, can't be also a transient
                // nor a mapped field
                continue;
            }

            $transientAnnot = $this->reader->getPropertyAnnotation($property, Transient::class);
            if ($transientAnnot) {
                $metadata->skipTransientField($property->getName());

                // if it's a transiend, can't be also a mapped field
                continue;
            }

            $metadata->mapField([
                'fieldName' => $property->getName(),
            ]);
        }
    }

    /**
     * Gets the names of all mapped classes known to this driver.
     *
     * @return array The names of all mapped classes known to this driver.
     */
    public function getAllClassNames()
    {
        return [];
    }

    /**
     * Whether the class with the specified name should have its metadata loaded.
     * This is only the case if it is either mapped as an Entity or a
     * MappedSuperclass.
     *
     * @param string $className
     *
     * @return bool
     */
    public function isTransient($className)
    {
        return false;
    }
}

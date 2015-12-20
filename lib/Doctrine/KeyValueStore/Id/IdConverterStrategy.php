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

/**
 * Serialization Interface for Identifiers
 *
 * This is used to simply convert other entities to serialized identifiers
 * and back, for example if an Object-Relational-Mapper Entity is part
 * of a key this strategy converts it to its identifier and back.
 *
 * @author Benjamin Eberlei <kontakt@beberlei.de>
 */
interface IdConverterStrategy
{
    /**
     * Serialize data for the persistence.
     *
     * @param string $class
     * @param mixed  $data
     *
     * @return string
     */
    public function serialize($class, $data);

    /**
     * Unserialize data from the persistence system.
     *
     * @param string $class
     * @param mixed  $data
     *
     * @return mixed
     */
    public function unserialize($class, $data);
}

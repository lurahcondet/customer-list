<?php

/**
 * Customer List
 *
 * @author Didi Kusnadi<jalapro08@gmail.com>
 *
 */

namespace CustomerKit;

class Customer
{
    /**
     * source table
     */
    const SOURCE_DB = 'resource.json';

    /**
     * sequence table
     */
    const SEQUENCE_DB = 'sequence.json';

    /**
     * db path
     */
    const DB_PATH = 'db';

    /**
     * add data
     * @param array $data [description]
     * @return void
     */
    public function add($data = [])
    {
        if (!count($data)) {
            throw new \Exception("Invalid input data");
        }

        $customerId = 0;
        if (isset($data['id']) && $data['id']) {
            $customerId = (int)$data['id'];
            $this->edit($customerId, $data);
            return;
        }

        unset($data['id']);

        $entityId = $this->getLastInsertId() + 1;
        $data = array_merge(['id' => $entityId], $data);
        $source = $this->read(self::SOURCE_DB);
        $source[] = $data;

        $this->write($source, self::SOURCE_DB);
        $this->setLastInsertId($entityId);
    }

    /**
     * edit data
     * @param int $customerId
     * @param array $data
     * @return void
     */
    public function edit($customerId, $data = [])
    {
        if (!count($data)) {
            throw new \Exception("Invalid input data");
        }

        $source = $this->read(self::SOURCE_DB);
        foreach ($source as $key => $item) {
            if ($item['id'] == $customerId) {
                $data['id'] = $customerId;
                if (empty($data['password'])) {
                    $data['password'] = $item['password'];
                }
                $source[$key] = $data;
            }
        }

        $this->write($source, self::SOURCE_DB);
    }

    /**
     * delete data
     * @param int $customerId
     * @return void
     */
    public function delete($customerId)
    {
        if (!$customerId) {
            throw new \Exception("Invalid identifier");
        }

        $source = $this->read(self::SOURCE_DB);
        if (count($source)) {
            foreach ($source as $key => $item) {
                if ($item['id'] == $customerId) {
                    unset($source[$key]);
                }
            }
            $this->write($source, self::SOURCE_DB);
        }
    }

    /**
     * read source
     * @return string
     */
    public function get()
    {
        $resource = $this->read(self::SOURCE_DB);
        foreach (array_keys($resource) as $key) {
            $resource[$key]['password'] = '**********';
        }
        return $resource;
    }


    /**
     * read source
     * @param  string $file
     * @return array
     */
    public function read($file)
    {
        return $this->toArray(
            file_get_contents($this->getTable($file))
        );
    }

    /**
     * write source
     * @param  array $data
     * @param  string $file
     * @return void
     */
    protected function write($data, $file)
    {
        $content = $this->toJson($data);
        file_put_contents($this->getTable($file), $content);
    }

    /**
     * convert json string to array
     * @param  string $content
     * @return array
     * @throws Exception
     */
    protected function toArray($content)
    {
        try {
            return json_decode($content, true);
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * convert data to json
     * @param  array  $content
     * @return void
     */
    protected function toJson($content = [])
    {
        return json_encode($content, JSON_PRETTY_PRINT);
    }

    /**
     * get table
     * @param  string $tableName
     * @return string
     */
    protected function getTable($tableName)
    {
        return self::DB_PATH . '/' . $tableName;
    }

    /**
     * get last insert id
     * @return int
     */
    protected function getLastInsertId()
    {
        try {
            $resource = $this->read(self::SEQUENCE_DB);
            return (int)$resource[self::SOURCE_DB];
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * get last insert id
     * @param  int $entityId
     * @return int
     */
    protected function setLastInsertId($entityId)
    {
        try {
            $resource = $this->read(self::SEQUENCE_DB);
            $resource[self::SOURCE_DB] = $entityId;
            $this->write($resource, self::SEQUENCE_DB);
        } catch (\Exception $e) {
            return 0;
        }
    }
}

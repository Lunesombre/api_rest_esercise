<?php

namespace App\Crud;

use App\Crud\Exception\UnprocessableContentException;
use PDO;

class ProductsCrud
{
    public function __construct(private PDO $pdo)
    {
    }
    /**
     * Creates a new product
     *
     * @param array $data name, base price & description (optional)
     * @return boolean Number of affected rows === 1 (=> created)
     * @throws Exception
     */
    public function create(array $data): int
    {
        if (!isset($data['name']) || !isset($data['basePrice'])) {
            throw new UnprocessableContentException("Name and base price are required");
        }
        $query = "INSERT INTO products VALUES (null, :product_name, :product_base_price, :product_description)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            'product_name' => $data['name'],
            'product_base_price' => $data['basePrice'],
            'product_description' => $data['description']
        ]);
        return $this->pdo->lastInsertId();
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM products");
        $products = $stmt->fetchAll();
        return ($products === false) ? [] : $products;
        // (à cause de ce que peut renvoyer fetchAll)
    }

    public function find(int $id): ?array
    {
        return [];
    }

    public function update(int $id, array $data): bool
    {
        return true;
    }

    public function delete(int $id): bool
    {
        return true;
    }
}

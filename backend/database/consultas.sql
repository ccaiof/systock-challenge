-- Os usuários com mais produtos
SELECT users."name", count(p.id) AS products_count FROM users
LEFT JOIN products p ON p.user_id = users.id
GROUP BY users."name"
ORDER BY products_count DESC;

-- Exibir a quantidade de produtos por faixa de preço
SELECT
    CASE
        WHEN preco BETWEEN 0 AND 100 THEN '0-100'
        WHEN preco BETWEEN 101 AND 500 THEN '101-500'
        WHEN preco BETWEEN 501 AND 1000 THEN '501-1000'
        ELSE '1000+'
    END AS price_range,
    COUNT(*) AS total
FROM products
GROUP BY price_range
ORDER BY price_range;

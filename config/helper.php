<?php
// CRUD Operations
function insert($conn, $table, $data)
{
    try {
        // Create column names and placeholders
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        // Prepare the SQL statement
        $query = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $conn->prepare($query);

        // Bind parameters
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        // Execute the statement
        $stmt->execute();
    } catch (PDOException $e) {
        error_log("Insert Error: " . $e->getMessage()); // Log error instead of echo
    }
}

function read($conn, $table, ?array $where = null)
{
    try {
        if ($where) {
            // Build the WHERE clause with placeholders
            $conditions = [];
            foreach ($where as $key => $value) {
                $conditions[] = "$key = :$key";
            }
            $whereClause = implode(' AND ', $conditions);
            $query = "SELECT * FROM $table WHERE $whereClause";
        } else {
            $query = "SELECT * FROM $table";
        }

        // Prepare the SQL statement
        $stmt = $conn->prepare($query);

        // Bind parameters if a WHERE clause is present
        if ($where) {
            foreach ($where as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
        }

        // Execute the statement
        $stmt->execute();

        // Fetch all results as an associative array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        error_log("Read Error: " . $e->getMessage()); // Log error instead of echo
        return false;
    }
}


function update($conn, $table, $data, $where)
{
    try {
        // Build the SET clause
        $setClause = [];
        foreach ($data as $key => $value) {
            $setClause[] = "$key = :$key";
        }
        $setClauseString = implode(', ', $setClause);

        // Prepare the SQL statement
        $query = "UPDATE $table SET $setClauseString WHERE $where";
        $stmt = $conn->prepare($query);

        // Bind parameters
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        // Execute the statement
        $stmt->execute();
    } catch (PDOException $e) {
        error_log("Update Error: " . $e->getMessage()); // Log error instead of echo
    }
}

function delete($conn, $table, $where)
{
    try {
        if (is_array($where)) {
            // Build the WHERE clause with placeholders
            $conditions = [];
            foreach ($where as $key => $value) {
                $conditions[] = "$key = :$key";
            }
            $whereClause = implode(' AND ', $conditions);
        } else {
            $whereClause = $where;
        }

        // Prepare the SQL statement
        $query = "DELETE FROM $table WHERE $whereClause";
        $stmt = $conn->prepare($query);

        // Bind parameters if $where is an array
        if (is_array($where)) {
            foreach ($where as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
        }

        // Execute the statement
        $stmt->execute();
    } catch (PDOException $e) {
        error_log("Delete Error: " . $e->getMessage()); // Log error instead of echo
    }
}

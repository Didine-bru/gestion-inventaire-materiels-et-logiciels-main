<?php
class MY_Model extends CI_Model
{
    public $table;

    public function __construct()
    {
        parent::__construct();
        $this->table = '';
    }

    /**
     *  Insère une nouvelle ligne dans la base de données.
     * @param array $escaped_options
     * @param array $not_escaped_options
     * @return bool
     */
    public function create($escaped_options = array(), $not_escaped_options = array())
    {
        return $this->create_into_table($this->table, $escaped_options, $not_escaped_options);
    }

    public function create_into_table($table, $escaped_options = array(), $not_escaped_options = array())
    {
        //  Vérification des données à insérer
        if (empty($escaped_options) AND empty($not_escaped_options)) {
            return false;
        }

        return (bool)$this->db->set($escaped_options)
            ->set($not_escaped_options, null, false)
            ->insert($table);
    }

    /**
     *  Récupère des données dans la base de données.
     * @param string $select
     * @param array $where
     * @param null $nb
     * @param null $debut
     * @param array $orders
     * @param array $likes
     * @param bool $distinct
     * @return
     */
    public function read($select = '*', $where = array(), $nb = null, $debut = null, $orders = array(),  $likes = array(), $distinct = false)
    {
        return $this->read_from_table($this->table, $select, $where, $nb, $debut, $orders, $likes, $distinct);
    }

    public function read_from_table($table, $select = '*', $where = array(), $nb = null, $debut = null, $orders = array(),  $likes = array(), $distinct = false)
    {
        $this->db->select($select)
            ->from($table)
            ->where($where);

        if (isset($nb) && isset($debut))
            $this->db->limit($nb, $debut);
        if ($distinct)
            $this->db->distinct();
        if (!empty($orders)) {
            foreach ($orders as $order)
                $this->db->order_by($order[0], $order[1]);
        }

        if(!empty($likes)){
            foreach($likes as $key => $value) {
                $this->db->or_like($key, $value, 'both');
            }
        }

        return $this->db
            ->get()
            ->result();
    }

    public function read_with_join($select = '*', $join_options = array(), $where = array(), $nb = null, $debut = null, $orders = array(), $likes = array(), $distinct = false)
    {
        return $this->read_with_join_from_table($this->table, $select, $join_options, $where, $nb, $debut, $orders,  $likes, $distinct);
    }

    public function read_with_join_from_table($table, $select = '*', $join_options = array(), $where = array(), $nb = null, $debut = null, $orders = array(),  $likes = array(), $distinct = false)
    {
        if (empty($join_options))
            return $this->read_from_table($table, $select, $where, $nb, $debut);

        $this->db->select($select)
            ->from($table);
        foreach ($join_options as $join_table) {
            if (sizeof($join_table) == 3)
                $this->db->join($join_table[0], $join_table[1], $join_table[2]);
            else
                $this->db->join($join_table[0], $join_table[1]);
        }

        $this->db
            ->where($where);

        if (isset($nb) && isset($debut))
            $this->db->limit($nb, $debut);

        if ($distinct)
            $this->db->distinct();
        if (!empty($orders)) {
            foreach ($orders as $order)
                $this->db->order_by($order[0], $order[1]);
        }

        if(!empty($likes)){
            foreach($likes as $key => $value) {
                $this->db->or_like($key, $value, 'both');
            }
        }

        return $this->db
            ->get()
            ->result();
    }


    /**
     *  Modifie une ou plusieurs lignes dans la base de données.
     * @param $where
     * @param array $escaped_options
     * @param array $not_escaped_options
     * @return bool
     */
    public function update($where, $escaped_options = array(), $not_escaped_options = array())
    {
        return $this->update_into_table($this->table, $where, $escaped_options, $not_escaped_options);
    }

    public function update_into_table($table, $where, $escaped_options = array(), $not_escaped_options = array())
    {
        if (empty($escaped_options) AND empty($not_escaped_options)) {
            return false;
        }

        if (!is_array($where)) {
            $where = array('id' => $where);
        }

        return (bool)$this->db->set($escaped_options)
            ->set($not_escaped_options, null, false)
            ->where($where)
            ->update($table);
    }


    /**
     *  Supprime une ou plusieurs lignes de la base de données.
     * @param $where
     * @return bool
     */
    public function delete($where)
    {
        return $this->delete_into_table($this->table, $where);
    }

    public function delete_into_table($table, $where)
    {
        if (!is_array($where)) {
            $where = array('id' => $where);
        }

        return (bool)$this->db->where($where)
            ->delete($table);
    }


    /**
     *  Retourne le nombre de résultats.
     * @param array $champ
     * @param null $value
     * @return int
     */
    public function count($champ = array(), $value = null) // Si $champ est un array, la variable $valeur sera ignorée par la méthode where()
    {
        return (int)$this->db->where($champ, $value)
            ->from($this->table)
            ->count_all_results();
    }
}
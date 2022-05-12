<?php
// Controller For DataTable ServerSide
// include "component/config.php";
    $tablecoloms = "kode_mata_kuliah,nama_mata_kuliah,nama_program_studi";
    $table = 'getmatakuliah';
    $line = explode(",", $tablecoloms);
    // $columns = array($line);

    $querycount = $db->query("SELECT count(*) as jumlah FROM $table");
    $datacount = $querycount->fetch_array();
      $totalData = $datacount['jumlah'];
      $totalFiltered = $totalData;
      $limit = $_POST['length'];
      $start = $_POST['start'];
    
      $order = $line[$_POST['order']['0']['column']];
      $dir = $_POST['order']['0']['dir'];
      if(empty($_POST['search']['value']))
      {$query = $db->query("SELECT * FROM $table order by $order $dir LIMIT $limit OFFSET $start");
      }
      else {
          $search = $_POST['search']['value'];
          $query = $db->query("SELECT * FROM $table WHERE CONCAT(".$tablecoloms.") LIKE '%$search%' order by $order $dir LIMIT $limit OFFSET $start");
         $querycount = $db->query("SELECT count(*) as jumlah FROM $table WHERE CONCAT(".$tablecoloms.") LIKE '%$search%'");
       $datacount = $querycount->fetch_array();
         $totalFiltered = $datacount['jumlah'];
      }

      $data = array();
      if(!empty($query))
      {
          $no = $start + 1;
          while ($r = $query->fetch_array())
          {
              $nestedData['no'] = $no;
              $nestedData['kode_mata_kuliah'] = "<pre>".$r['kode_mata_kuliah'];
              $nestedData['nama_mata_kuliah'] = "<pre>".$r['nama_mata_kuliah'];
              $nestedData['nama_program_studi'] = "<pre>".$r['nama_program_studi'];
              $nestedData['sks_mata_kuliah'] = "<pre>".$r['sks_mata_kuliah'];
              $nestedData['id_matkul'] = "<pre>".$r['id_matkul'];
              // $nestedData['id_kelas_kuliah'] = "<pre>".$r['id_kelas_kuliah'];
              $data[] = $nestedData;
              $no++;
          }
      }

      $json_data = array(
                  "draw"            => intval($_POST['draw']),
                  "recordsTotal"    => intval($totalData),
                  "recordsFiltered" => intval($totalFiltered),
                  "data"            => $data
                  );
      echo json_encode($json_data);


?>
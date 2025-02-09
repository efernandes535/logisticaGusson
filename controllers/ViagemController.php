<?php
require_once __DIR__ . '/../models/Viagem.php';
class ViagemController
{
     private $viagemModel;

    public function __construct(PDO $conexao)
    {
        $this->viagemModel = new Viagem($conexao);
    }

     public function index($motoristaSelecionado, $dataInicio, $dataFim){
        if (empty($motoristaSelecionado) && empty($dataInicio) && empty($dataFim)){
             return $this->viagemModel->getAll();
        }
      return $this->viagemModel->getAllWithFilters($motoristaSelecionado, $dataInicio, $dataFim);
     }
    public function add()
    {
        // Lógica para adicionar uma viagem
          return $this->viagemModel->create($_POST);
    }

    public function edit($id)
    {
       // Lógica para editar uma viagem
        return $this->viagemModel->find($id);
    }
    public function update($id)
    {
      return $this->viagemModel->update($id, $_POST);
    }

    public function delete($id)
    {
        // Lógica para excluir uma viagem
        return $this->viagemModel->delete($id);
    }

      public function view($id)
    {
        // Lógica para visualizar uma viagem
         return $this->viagemModel->find($id);
    }
     public function kmByPlaca($placaSelecionada, $dataInicio, $dataFim)
     {
         return $this->viagemModel->getAllKmByPlacaWithFilters($placaSelecionada, $dataInicio, $dataFim);
     }
     public function listDistinctMotoristas()
     {
         return  $this->viagemModel->getDistinctMotoristas();
     }
     public function listDistinctPlacas()
     {
         return $this->viagemModel->getDistinctPlacas();
     }
        public function suggestMotorista()
    {
       $motoristas =  $this->viagemModel->getMotoristaDisponibilidade();
        $motoristasKm = $this->viagemModel->getMotoristaKmRodado();
         $disponiveis = [];
           $viajando = [];
          $media = 0;
          $totalKm = 0;
        foreach ($motoristasKm as $motoristaKm) {
             $totalKm += $motoristaKm['total_km_rodado'];
        }

       if(count($motoristasKm) > 0){
         $media = $totalKm / count($motoristasKm);
       }
          foreach ($motoristas as $motorista => $status) {
               if($status == 'disponivel'){
                   $kmRodado = 0;
                   foreach ($motoristasKm as $motoristaKm) {
                        if($motoristaKm['motorista'] == $motorista){
                             $kmRodado = $motoristaKm['total_km_rodado'];
                         }
                    }
                  $disponiveis[$motorista] = [
                        'status'=> $status,
                         'kmRodado' =>  $kmRodado,
                         'diff' => $media - $kmRodado
                      ];
               }
              else{
                 $viajando[$motorista] = ['status'=> $status];
              }
          }
            if (empty($disponiveis)){
                return ['motorista_sugestao' => 'Nenhum motorista disponível', 'disponiveis' => [], 'viajando'=> $viajando];
            }
             usort($disponiveis, function($a, $b) {
                return $b['diff'] <=> $a['diff'];
            });

             return ['motorista_sugestao' => $disponiveis[0]['motorista'],  'disponiveis' => $disponiveis, 'viajando' => $viajando];
    }
     public function getMotoristaDisponibilidade(){
          return  $this->viagemModel->getMotoristaDisponibilidade();
      }
}
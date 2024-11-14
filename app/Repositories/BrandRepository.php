 <?php



use Illuminate\Database\Eloquent\Model;

class BrandRepository{
    public function __construct(Model $model){
        $this->model = $model;
    }


    public function selectAtributosRegistrosRelacionados($atributos){
        $this->model = $this->model->with($atributos);
        //query sendo montada
    }


    public function filtro($filtros){
        $filtros = explode(';',$filtros);
        foreach($filtros as $key => $condicao){
            $c = explode(':',$codicao);
            $this->model = $this->model->where($c[0],$c[1],$c[2]);
        }
    }

    public function selectAtributos($atributos){
        $this->model = $this->model->selectRaw($atributos);
    }

    public function getResult(){
        return $this->model->get();
    }




}



?>
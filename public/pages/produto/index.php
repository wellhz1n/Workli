<title>Serviços</title>

<!-- LINKS EXTERNOS -->
<link href="plugins/fancybox/fancybox.min.css" type="text/css" rel="stylesheet">

<!-- LINKS INTERNOS -->
<link rel="stylesheet" href="pages/produto/style.css">

<div class="container-produto">
  <div class="row containerFilhoProduto">

    <main class="col-md-12 ">

      <header class="border-bottom mb-4 pb-3">
        <div class="form-inline">
          <span class="mr-md-auto">32 Itens Encontrados</span>
          <select class="mr-2 form-control">
            <option>Melhores avaliados</option>
            <option>Primeiros à aceitar</option>
            <option>Most Populares</option>
            <option>Mais baratos</option>
          </select>
          <div class="btn-group">
            <a href="#" class="btn btn-outline-secondary active" data-toggle="tooltip" title="" data-original-title="List view">
              <i class="fa fa-bars"></i></a>
            <a href="#" class="btn  btn-outline-secondary" data-toggle="tooltip" title="" data-original-title="Grid view">
              <i class="fa fa-th"></i></a>
          </div>
        </div>
      </header>


      <article class="card card-product-list">
        <div class="row no-gutters">
          <aside class="col-md-3">
            <a href="#" class="img-wrap">
              <span class="badge badge-danger"> NOVO </span>
              <img src="images/items/3.jpg">
            </a>
          </aside>
          <div class="col-md-6">
            <div class="info-main">
              <a href="#" class="h5 title"> José Bezerra</a>
              <div class="rating-wrap mb-3">
                <ul class="rating-stars">
                  <li style="width:80%" class="stars-active">
                    <i class="fa fa-star"></i> <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i> <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                  </li>
                  <li>
                    <i class="fa fa-star"></i> <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i> <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                  </li>
                </ul>
                <div class="label-rating">7/10</div>
              </div>

              <p> Take it as demo specs, ipsum dolor sit amet, consectetuer adipiscing elit, Lorem ipsum dolor sit amet, consectetuer adipiscing elit, Ut wisi enim ad minim veniam </p>
            </div>
          </div>
          <aside class="col-sm-3">
            <div class="info-aside">
              <div class="price-wrap">
                <span class="price h5">$140</span>
                <del class="price-old">$198</del>
              </div>
              <p class="text-success">Free shipping</p>
              <br>
              <p>
                <a href="#" class="btn btn-primary btn-block"> Contratar </a>
                <a href="#" class="btn btn-light btn-block"><i class="fa fa-heart"></i>
                  <span class="text">Detalhes</span>
                </a>
              </p>
            </div>
          </aside>
        </div>
      </article>
      



      <nav aria-label="Page navigation sample">
        <ul class="pagination">
          <li class="page-item disabled"><a class="page-link" href="#">Anterior</a></li>
          <li class="page-item active"><a class="page-link" href="#">1</a></li>
          <li class="page-item"><a class="page-link" href="#">2</a></li>
          <li class="page-item"><a class="page-link" href="#">3</a></li>
          <li class="page-item"><a class="page-link" href="#">Próximo</a></li>
        </ul>
      </nav>

    </main>

  </div>

</div>
<script type="application/javascript" src="pages/produto/script.js"></script>
<script type="application/javascript">
    
    $("#Titulo").text("Produtos");
</script>
<!DOCTYPE html>
<html>
<head>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
  /* font-size: 8px; */
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
.camAdrress{
    color: black;
    width: 40%;
}
.left{
    direction: rtl;
    width: 40%;
}
.image{
    width: 20%;
}
.img{
    width: 100px;
    height: 100px;
    align-items: center;
}
.table{
    border: none;
}
.item{
    font-size: 12px;
}
</style>
</head>
<body>
<div>
    <div >
        <table class="table">
            <tr>
                <td class="camAdrress">
                    Cam and Motor Primary School <br>
                    Eiffel Flats<br>
                    Kadoma<br>
                    Box 250<br>
                    Tel<br>
                </td>
                <td class="image">
                    <img class="img" src="{{asset('coat.jfif')}}" alt="coat">
                </td>
                <td class="left">
                    Product:    {{ $product_name }} <br>
                    Generated on: {{ $date }}
                </td>
            </tr>
        </table>
    </div>
</div>
<h1>DEBTORS LIST FOR {{ $product_name }}</h1> <span>Generated on: {{ $date }}</span>

<table>
  <tr style="background-color:lime;">
    <th>Name</th>
    <th>Surname</th>
    <th>Class</th>
    <th>Balance</th>
  </tr>
  @foreach ($debtorsForOneItem as $pupil)
      <tr class="item">
                <th>{{ $pupil[0] }}</th>
                <th>{{ $pupil[1] }}</th>
                <th>{{ $pupil[2] }}</th>
                <th>{{ $pupil[3] }}</th>
      </tr>
      @endforeach
</table>

</body>
</html>

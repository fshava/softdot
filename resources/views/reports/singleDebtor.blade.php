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
                    Name:    {{ $pupil->name }} <br>
                    Surname: {{ $pupil->surname }} <br>
                    Class    {{ $pupil->class }} <br>
                    Grade:   {{ $pupil->grade }} <br>
                    Sex:     {{ $pupil->sex }}
                </td>
            </tr>
        </table>
    </div>
</div>
<h1>INVOICE</h1> <span>Generated on: {{ $date }}</span>

<table>
  <tr style="background-color:lime;">
    <th>Year</th>
    <th>Term</th>
    <th>Description</th>
    <th>Balance</th>
  </tr>
  @foreach ($fee as $item)
      <tr class="item">
        <th>{{ $item->year }}</th>
        <th>{{ $item->term }}</th>
        <th>{{ $item->description }}</th>
        <th>{{ $item->pivot->balance }}</th>
      </tr>
      @endforeach
        <tr style="background-color:forestgreen;color:white">
            <th>Total</th>
            <th></th>
            <th></th>
            <th>{{ $total }}</th>
        </tr>
</table>

</body>
</html>

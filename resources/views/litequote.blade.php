<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>{{$quote->ref_number}}</title>

<style type="text/css">
    * {
        font-family: Verdana, Arial, sans-serif;
    }
    table{
        font-size: x-small;
    }
    tfoot tr td{
        font-weight: bold;
        font-size: x-small;
    }

    .gray {
        background-color: lightgray
    }
</style>

</head>
<body>

  <table width="100%">
    <tr>
        <td valign="top"><img src="{{asset('img/hftlogo.jpg')}}" alt="" width="300"/></td>
        <td align="right">
            <h3>HFT Optics</h3>
            <pre>
                Todd Ferguson
                9 Turnbury Ct. 
                Hawthorn Woods, IL 60061
                (224) 207-4600
            </pre>
        </td>
    </tr>

  </table>

  <table width="100%">
    <tr>
        <td><strong>From:</strong> Todd Ferguson todd@hftoptics.com</td>
        <td><strong>To:</strong> 
            {{$quote->customer}}<br> 
            {{$quote->customer_email}}
            <!-- {{$quote->billingAddress->address_1}}<br> 
            @if($quote->billingAddress->address_2 !== '')
            {{$quote->billingAddress->address_2}}<br>
            @endif
            {{$quote->billingAddress->city}}, {{$quote->billingAddress->state}} -->

        </td>
    </tr>
    <tr>
        <td><strong>Garaunteed For:</strong> 30 days</td>
    </tr>

  </table>

  <br/>

  <table width="100%">
    <thead style="background-color: lightgray;">
      <tr>
        <th>#</th>
        <th>Description</th>
        <th>Quantity</th>
        <th>Unit Price $</th>
        <th>Total $</th>
      </tr>
    </thead>
    <tbody>
        @foreach($quote->lineitems as $lineitem)
      <tr>
        <th scope="row"></th>
        <td>{{$lineitem->part}}</td>
        <td align="right">{{$lineitem->quantity}}</td>
        <td align="right">{{$lineitem->price}}</td>
        <td align="right">{{$lineitem->price * $lineitem->quantity}}</td>
      </tr>
      @endforeach
    </tbody>

    <tfoot>
        <tr>
            <td colspan="3"></td>
            <td align="right">Shipping $</td>
            <td align="right"></td>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td align="right"></td>
            <td align="right"></td>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td align="right">Total $</td>
            <td align="right" class="gray">$ {{$quote->total}}</td>
        </tr>
    </tfoot>
  </table>

</body>
</html>
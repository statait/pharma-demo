<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Sale</title>

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
    .font{
      font-size: 13px;
    }
    .authority {
        /*text-align: center;*/
        float: right
    }

    .authority1 {
        /*text-align: center;*/
        float: left
    }
    .authority h5 {
        margin-top: -10px;
        color: #037c09;
        /*text-align: center;*/
        margin-left: 35px;
    }

    .authority1 h5 {
        margin-top: -10px;
        color: #037c09;
        /*text-align: center;*/
        margin-left: 35px;
    }
    
    .thanks p {
        color: #136108;;
        font-size: 16px;
        font-weight: normal;
        font-family: serif;
        margin-top: 20px;
    }

    .t {
  border: 1px solid black;
  border-collapse: collapse;
}

</style>

</head>
<body>

  <table width="100%" style="background: #ffffff; padding:0 10px 0 10px;">
    <tr>
        <td valign="top">
          <!-- {{-- <img src="" alt="" width="150"/> --}} -->
          <br><br>
          
          <img width="150px" height="150px" src="assets/img/zuhairLogo.png" alt="">
          {{-- <h2 style="color: #ff7c00; font-size: 26px;"><strong>Zuhair Drug House</strong></h2> --}}
        </td>
        <td align="right">
            <pre class="font" >
              Zuhair Drug House
               Email:statabangladesh@gmail.com
               <br>
               Mob: 88 09678200509 
            </pre>
        </td>
    </tr>
  </table>


  <table width="100%" style="background:white; padding:2px;"></table>
  <table width="100%" style="background: #ffffff; padding:0 5px 0 5px;" class="font">
    <tr>
        <td>
          {{-- <p class="font" style="margin-left: 20px;">
           <strong>Name:</strong> {{ $quotation->customer->customer_name }}<br>
           <strong>Email:</strong> {{ $quotation->customer->email }} <br>
           <strong>Phone:</strong> {{  $quotation->customer->phone }} <br>
           <strong>Address:</strong> {{  $quotation->customer->address  }}
         </p> --}}
        </td>
        <td>
          <p class="font" style="margin-left: 20px; margin-top:-12px">
            <h3><span style="color: #106908;">Sale:</span> #{{ $salePharma->id }}</h3>
            <strong>Date:</strong> {{ $salePharma->date }} <br>
            {{-- <strong>Made By:</strong> {{ $quotation->auth->name }} <br> --}}
         </p>
        </td>
    </tr>
  </table>
  <br/>
<h3>Medicine List</h3>
  <table class="t" width="100%">
    <thead style="background-color: #026f91; color:#FFFFFF;">
      <tr class="font">
        <th class="t">SL.</th>
        <th class="t">Name</th>
        <th class="t">Unit Price </th>
        <th class="t">Quantity</th>
        <th class="t">Total </th>
      </tr>
    </thead>
    <tbody>
        @php
             $sl = 1;
        @endphp
     @foreach($saleItems as $item)
      <tr class="font">
        <td class="t" align="center">
                {{$sl++}}
        </td>
        <td class="t" align="center"> {{ $item->product->purchase->product }}</td>
        <td class="t" align="center">
            {{-- @if ($item->product->discount_price == NULL) --}}
             {{ $item->price  }} BDT
            {{-- @else
            TK {{ $item->product->discount_price  }}
            @endif --}}
        </td>

        <td class="t" align="center">{{ $item->quantity }}</td>
            
       
        {{-- <td class="t" align="center">
            @if ($item->product->discount_price == NULL)
            --
            @else
            TK {{ ($item->product->selling_price) - ($item->product->discount_price) }}
            @endif
        </td>
         --}}
        <td class="t" align="center">TK {{ $item->total_price }} </td>
      </tr>
      @endforeach
      
    </tbody>
  </table>
  <br>
  <table width="100%" style=" padding:0 10px 0 10px;">
    <tr>
        <td align="right" >
         
          <hr>  
          <h3><span style="color: #148fa5;">Sub Total </span> <span style="font-size: 12px">{{ $salePharma->sub_total }}</span></h3>
          <h3><span style="color: #148fa5;">Discount </span>
            
            <span style="font-size: 12px">
              @if ($salePharma->discount_percentage == NULL && $salePharma->discount_flat == NULL)
               TK 0
            @elseif($salePharma->discount_percentage == NULL)
                {{ $salePharma->discount_flat }} BDT
            @else
                {{ $salePharma->discount_percentage  }}%
            @endif
            </span>
          </h3>
           
            {{-- <h2><span style="color: green;">Full Payment PAID</h2> --}}
            <hr>
            @if ( $salePharma->paid == null)
              <h3><span style="color: #148fa5;">Paid Amount</span> <span style="font-size: 12px"> TK 0</span></h3>
              <h3><span style="color: #148fa5;">Change Amount</span> <span style="font-size: 15px"> TK {{ $salePharma->grand_total }}</span></h3>
            @else
              <h3><span style="color: #148fa5;">Paid Amount</span> <span style="font-size: 12px"> TK {{$salePharma->paid}}</span></h3>
              <h3><span style="color: #148fa5;">Change Amount</span> <span style="font-size: 15px"> TK {{ $salePharma->due }}</span></h3>
            @endif
          
        </td>

    </tr>
    <br>
    {{-- <tr>
       <td><b> Quotation Details : </b>{{$quotation->details}}</td> 
    </tr> --}}
  </table>
  
  {{-- <div class="thanks mt-3">
    <p>Thanks For Buying Products..!!</p>
  </div> --}}
  {{-- <div class="authority1 float-left" style="margin-top:150px">
  <p>-----------------------------------</p>
  <h5>Customer Signature:</h5>
  </div> --}}
  <div class="authority float-right">
    {{-- <div class="seal">
      <img width="110" height="112" src="frontend/assets/img/auth_seal.png" alt="">
    </div> --}}
    <br>
      <p>-----------------------------------</p>
      <h5 style="color: #148fa5">Authority Signature:</h5>
  </div>
</body>
</html> 
<div id="printable">
	<style type="text/css">

		body{margin:0;padding:0;}
		.tableDiv{width:100%;margin:0 auto;}
		.titleHeader{text-align: center;}
        .report{width: 100%;}
		.report, td, th {border: 1px solid black; border-collapse: collapse;}
		th{font-size: 14px; padding:5px;background-color:#dfdfdf; text-align:center;}
		td{font-size: 14px;padding:10px;color:#000; text-align:center;}
        .clearfix{clear: both;}
	</style>

	@php


    $i = 0;
    $shrawan1=0;	$bhadra1=0;	$aswin1=0;	$kartik1=0;	$mansir1=0;	$paush1=0;	$magh1=0;	$falgun1=0;	$chaitra1=0;	$baishakh1=0;	$jestha1=0;	$asad1=0;
	$shrawan2=0;	$bhadra2=0;	$aswin2=0;	$kartik2=0;	$mansir2=0;	$paush2=0;	$magh2=0;	$falgun2=0;	$chaitra2=0;	$baishakh2=0;	$jestha2=0;	$asad2=0;
	$shrawan3=0;	$bhadra3=0;	$aswin3=0;	$kartik3=0;	$mansir3=0;	$paush3=0;	$magh3=0;	$falgun3=0;	$chaitra3=0;	$baishakh3=0;	$jestha3=0;	$asad3=0;
	$shrawan4=0;	$bhadra4=0;	$aswin4=0;	$kartik4=0;	$mansir4=0;	$paush4=0;	$magh4=0;	$falgun4=0;	$chaitra4=0;	$baishakh4=0;	$jestha4=0;	$asad4=0;
	$shrawan5=0;	$bhadra5=0;	$aswin5=0;	$kartik5=0;	$mansir5=0;	$paush5=0;	$magh5=0;	$falgun5=0;	$chaitra5=0;	$baishakh5=0;	$jestha5=0;	$asad5=0;
	$shrawan6=0;	$bhadra6=0;	$aswin6=0;	$kartik6=0;	$mansir6=0;	$paush6=0;	$magh6=0;	$falgun6=0;	$chaitra6=0;	$baishakh6=0;	$jestha6=0;	$asad6=0;






    foreach ($deliveries as $key => $record) {
        foreach ($record as $row) {
            if($i==0){
            	if($row->caste=='1'){
            		$shrawan1++;
            	}elseif($row->caste=='2'){
            		$shrawan2++;
            	}elseif($row->caste=='3'){
            		$shrawan3++;
            	}elseif($row->caste=='4'){
            		$shrawan4++;
            	}elseif($row->caste=='5'){
            		$shrawan5++;
            	}elseif($row->caste=='6'){
            		$shrawan6++;
            	}
            }
            if($i==1){
            	if($row->caste=='1'){
            		$bhadra1++;
            	}elseif($row->caste=='2'){
            		$bhadra2++;
            	}elseif($row->caste=='3'){
            		$bhadra3++;
            	}elseif($row->caste=='4'){
            		$bhadra4++;
            	}elseif($row->caste=='5'){
            		$bhadra5++;
            	}elseif($row->caste=='6'){
            		$bhadra6++;
            	}
            }
            if($i==2){
            	if($row->caste=='1'){
            		$aswin1++;
            	}elseif($row->caste=='2'){
            		$aswin2++;
            	}elseif($row->caste=='3'){
            		$aswin3++;
            	}elseif($row->caste=='4'){
            		$aswin4++;
            	}elseif($row->caste=='5'){
            		$aswin5++;
            	}elseif($row->caste=='6'){
            		$aswin6++;
            	}
            }
            if($i==3){
            	if($row->caste=='1'){
            		$kartik1++;
            	}elseif($row->caste=='2'){
            		$kartik2++;
            	}elseif($row->caste=='3'){
            		$kartik3++;
            	}elseif($row->caste=='4'){
            		$kartik4++;
            	}elseif($row->caste=='5'){
            		$kartik5++;
            	}elseif($row->caste=='6'){
            		$kartik6++;
            	}
            }
            if($i==4){
            	if($row->caste=='1'){
            		$mansir1++;
            	}elseif($row->caste=='2'){
            		$mansir2++;
            	}elseif($row->caste=='3'){
            		$mansir3++;
            	}elseif($row->caste=='4'){
            		$mansir4++;
            	}elseif($row->caste=='5'){
            		$mansir5++;
            	}elseif($row->caste=='6'){
            		$mansir6++;
            	}
            }
            if($i==5){
            	if($row->caste=='1'){
            		$paush1++;
            	}elseif($row->caste=='2'){
            		$paush2++;
            	}elseif($row->caste=='3'){
            		$paush3++;
            	}elseif($row->caste=='4'){
            		$paush4++;
            	}elseif($row->caste=='5'){
            		$paush5++;
            	}elseif($row->caste=='6'){
            		$paush6++;
            	}
            }
            if($i==6){
            	if($row->caste=='1'){
            		$magh1++;
            	}elseif($row->caste=='2'){
            		$magh2++;
            	}elseif($row->caste=='3'){
            		$magh3++;
            	}elseif($row->caste=='4'){
            		$magh4++;
            	}elseif($row->caste=='5'){
            		$magh5++;
            	}elseif($row->caste=='6'){
            		$magh6++;
            	}
            }
            if($i==7){
            	if($row->caste=='1'){
            		$falgun1++;
            	}elseif($row->caste=='2'){
            		$falgun2++;
            	}elseif($row->caste=='3'){
            		$falgun3++;
            	}elseif($row->caste=='4'){
            		$falgun4++;
            	}elseif($row->caste=='5'){
            		$falgun5++;
            	}elseif($row->caste=='6'){
            		$falgun6++;
            	}
            }
            if($i==8){
            	if($row->caste=='1'){
            		$chaitra1++;
            	}elseif($row->caste=='2'){
            		$chaitra2++;
            	}elseif($row->caste=='3'){
            		$chaitra3++;
            	}elseif($row->caste=='4'){
            		$chaitra4++;
            	}elseif($row->caste=='5'){
            		$chaitra5++;
            	}elseif($row->caste=='6'){
            		$chaitra6++;
            	}
            }
            if($i==9){
            	if($row->caste=='1'){
            		$baishakh1++;
            	}elseif($row->caste=='2'){
            		$baishakh2++;
            	}elseif($row->caste=='3'){
            		$baishakh3++;
            	}elseif($row->caste=='4'){
            		$baishakh4++;
            	}elseif($row->caste=='5'){
            		$baishakh5++;
            	}elseif($row->caste=='6'){
            		$baishakh6++;
            	}
            }
            if($i==10){
            	if($row->caste=='1'){
            		$jestha1++;
            	}elseif($row->caste=='2'){
            		$jestha2++;
            	}elseif($row->caste=='3'){
            		$jestha3++;
            	}elseif($row->caste=='4'){
            		$jestha4++;
            	}elseif($row->caste=='5'){
            		$jestha5++;
            	}elseif($row->caste=='6'){
            		$jestha6++;
            	}
            }
            if($i==11){
            	if($row->caste=='1'){
            		$asad1++;
            	}elseif($row->caste=='2'){
            		$asad2++;
            	}elseif($row->caste=='3'){
            		$asad3++;
            	}elseif($row->caste=='4'){
            		$asad4++;
            	}elseif($row->caste=='5'){
            		$asad5++;
            	}elseif($row->caste=='6'){
            		$asad6++;
            	}
            }
        }
       $i++;
    }

    $shrawanTotal = $shrawan1+$shrawan2+$shrawan3+$shrawan4+$shrawan5+$shrawan6;
    $bhadraTotal = $bhadra1+$bhadra2+$bhadra3+$bhadra4+$bhadra5+$bhadra6;
    $aswinTotal = $aswin1+$aswin2+$aswin3+$aswin4+$aswin5+$aswin6;
    $kartikTotal = $kartik1+$kartik2+$kartik3+$kartik4+$kartik5+$kartik6;
    $mansirTotal = $mansir1+$mansir2+$mansir3+$mansir4+$mansir5+$mansir6;
    $paushTotal = $paush1+$paush2+$paush3+$paush4+$paush5+$paush6;
    $maghTotal = $magh1+$magh2+$magh3+$magh4+$magh5+$magh6;
    $falgunTotal = $falgun1+$falgun2+$falgun3+$falgun4+$falgun5+$falgun6;
    $chaitraTotal = $chaitra1+$chaitra2+$chaitra3+$chaitra4+$chaitra5+$chaitra6;
    $baishakhTotal = $baishakh1+$baishakh2+$baishakh3+$baishakh4+$baishakh5+$baishakh6;
    $jesthaTotal = $jestha1+$jestha2+$jestha3+$jestha4+$jestha5+$jestha6;
    $asadTotal = $asad1+$asad2+$asad3+$asad4+$asad5+$asad6;

    $caste1= $shrawan1+$bhadra1+$aswin1+$kartik1+$mansir1+$paush1+$magh1+$falgun1+$chaitra1+$baishakh1+$jestha1+$asad1;
    $caste2= $shrawan2+$bhadra2+$aswin2+$kartik2+$mansir2+$paush2+$magh2+$falgun2+$chaitra2+$baishakh2+$jestha2+$asad2;
    $caste3= $shrawan3+$bhadra3+$aswin3+$kartik3+$mansir3+$paush3+$magh3+$falgun3+$chaitra3+$baishakh3+$jestha3+$asad3;
    $caste4= $shrawan4+$bhadra4+$aswin4+$kartik4+$mansir4+$paush4+$magh4+$falgun4+$chaitra4+$baishakh4+$jestha4+$asad4;
    $caste5= $shrawan5+$bhadra5+$aswin5+$kartik5+$mansir5+$paush5+$magh5+$falgun5+$chaitra5+$baishakh5+$jestha5+$asad5;
    $caste6= $shrawan6+$bhadra6+$aswin6+$kartik6+$mansir6+$paush6+$magh6+$falgun6+$chaitra6+$baishakh6+$jestha6+$asad6;

    $allTotal = $caste1+$caste2+$caste3+$caste4+$caste5+$caste6;



	@endphp

    <div style="float: right; text-align: right; font-size: 12px;">HMIS 3.36</div>
    <div class="clearfix"></div>
	<div class="tableDiv">
		<h3 class="titleHeader">जात/जाती अनुसार स्वास्थ्य संस्था प्रसुति सेवा समायोजन फारम</h3>
		<table class="report">
			<tr>
				<th rowspan="2">जात/जाती</th>
				<th colspan="12" align="center">महिना</th>
				<th rowspan="2">जम्मा</th>
			</tr>
			
			<tr>
				<th>श्रावन</th>
				<th>भाद्र</th>
				<th>अस्विन</th>
				<th>कार्तिक</th>
				<th>मंसिर</th>
				<th>पाैष</th>
				<th>माघ</th>
				<th>फाल्गुन</th>
				<th>चैत</th>
				<th>वैसाख</th>
				<th>जेष्ठ</th>
				<th>असार</th>
			</tr>

			<tr>
				<td>1</td>
				<td>2</td>
				<td>3</td>
				<td>4</td>
				<td>5</td>
				<td>6</td>
				<td>7</td>
				<td>8</td>
				<td>9</td>
				<td>10</td>
				<td>11</td>
				<td>12</td>
				<td>13</td>
				<td>14</td>
			</tr>

			<tr>
				<td>दलित</td>
				<td>{{$shrawan1}}</td>
				<td>{{$bhadra1}}</td>
				<td>{{$aswin1}}</td>
				<td>{{$kartik1}}</td>
				<td>{{$mansir1}}</td>
				<td>{{$paush1}}</td>
				<td>{{$magh1}}</td>
				<td>{{$falgun1}}</td>
				<td>{{$chaitra1}}</td>
				<td>{{$baishakh1}}</td>
				<td>{{$jestha1}}</td>
				<td>{{$asad1}}</td>
				<td>{{$caste1}}</td>
			</tr>

			<tr>
				<td>जनजाति</td>
				<td>{{$shrawan2}}</td>	
				<td>{{$bhadra2}}</td>	
				<td>{{$aswin2}}</td>	
				<td>{{$kartik2}}</td>	
				<td>{{$mansir2}}</td>	
				<td>{{$paush2}}	</td>
				<td>{{$magh2}}</td>	
				<td>{{$falgun2}}</td>	
				<td>{{$chaitra2}}</td>	
				<td>{{$baishakh2}}</td>	
				<td>{{$jestha2}}</td>	
				<td>{{$asad2}}</td>
				<td>{{$caste2}}</td>
			</tr>

			<tr>
				<td>मधेसी</td>
				<td>{{$shrawan3}}</td>	
				<td>{{$bhadra3}}</td>	
				<td>{{$aswin3}}</td>
				<td>{{$kartik3}}</td>	
				<td>{{$mansir3}}</td>	
				<td>{{$paush3}}</td>	
				<td>{{$magh3}}</td>	
				<td>{{$falgun3}}</td>	
				<td>{{$chaitra3}}</td>	
				<td>{{$baishakh3}}</td>	
				<td>{{$jestha3}}</td>	
				<td>{{$asad3}}</td>
				<td>{{$caste3}}</td>
			</tr>

			<tr>
				<td>मुस्लिम</td>
				<td>{{$shrawan4}}</td>	
				<td>{{$bhadra4}}</td>	
				<td>{{$aswin4}}</td>
				<td>{{$kartik4}}</td>	
				<td>{{$mansir4}}</td>	
				<td>{{$paush4}}</td>	
				<td>{{$magh4}}</td>	
				<td>{{$falgun4}}</td>	
				<td>{{$chaitra4}}</td>	
				<td>{{$baishakh4}}</td>	
				<td>{{$jestha4}}</td>	
				<td>{{$asad4}}</td>
				<td>{{$caste4}}</td>
			</tr>

			<tr>
				<td>ब्राह्मण / क्षेत्री</td>
				<td>{{$shrawan5}}</td>	
				<td>{{$bhadra5}}</td>	
				<td>{{$aswin5}}</td>	
				<td>{{$kartik5}}</td>	
				<td>{{$mansir5}}</td>	
				<td>{{$paush5}}</td>	
				<td>{{$magh5}}</td>	
				<td>{{$falgun5}}</td>	
				<td>{{$chaitra5}}</td>	
				<td>{{$baishakh5}}</td>	
				<td>{{$jestha5}}</td>	
				<td>{{$asad5}}</td>
				<td>{{$caste5}}</td>
			</tr>

			<tr>
				<td>अन्य</td>
				<td>{{$shrawan6}}</td>	
				<td>{{$bhadra6}}</td>	
				<td>{{$aswin6}}</td>	
				<td>{{$kartik6}}</td>	
				<td>{{$mansir6}}</td>	
				<td>{{$paush6}}</td>
				<td>{{$magh6}}</td>	
				<td>{{$falgun6}}</td>	
				<td>{{$chaitra6}}</td>	
				<td>{{$baishakh6}}</td>	
				<td>{{$jestha6}}</td>	
				<td>{{$asad6}}</td>
				<td>{{$caste6}}</td>
			</tr>

			<tr>
				<td>जम्मा</td>
				<td>{{$shrawanTotal}}</td> 
			    <td>{{$bhadraTotal}}</td> 		    
			    <td>{{$aswinTotal}}</td> 
			    <td>{{$kartikTotal}}</td> 
			    <td>{{$mansirTotal}}</td> 
			    <td>{{$paushTotal}}</td> 
			    <td>{{$maghTotal}}</td>
			    <td>{{$falgunTotal}}</td> 
			    <td>{{$chaitraTotal}}</td> 
			    <td>{{$baishakhTotal}}</td>
			    <td>{{$jesthaTotal}}</td> 
			    <td>{{$asadTotal}}</td> 
			    <td>{{$allTotal}}</td>
			</tr>
		
		</table>
	</div>
</div>
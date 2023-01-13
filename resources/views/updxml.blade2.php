<{{"?"}}xml version="1.0" encoding="windows-1251" {{"?"}}>
       <Файл ИдФайл="{{$fileid}}" ВерсФорм="5.01" ВерсПрог="ZOD_Exchange 1.0">
              <СвУчДокОбор ИдОтпр="{{$fromid}}" ИдПол="{{$toid}}">
                     <СвОЭДОтпр ИННЮЛ="7605016030" ИдЭДО="2BE" НаимОрг="Общество с ограниченной ответственностью &quot;Компания &quot;Тензор&quot;&quot;" />
              </СвУчДокОбор>
              <Документ КНД="1115131" ВремИнфПр="{{$time}}" ДатаИнфПр="{{$date}}" НаимЭконСубСост="Общество с ограниченной ответственностью &quot;Нордком Карелия&quot;" Функция="СЧФДОП" ПоФактХЖ="Документ об отгрузке товаров (выполнении работ), передаче имущественных прав (документ об оказании услуг)" НаимДокОпр="Счет-фактура и документ об отгрузке товаров (выполнении работ), передаче имущественных прав (документ об оказании услуг)" СоглСтрДопИнф="1111.2222.0000">
                     <СвСчФакт НомерСчФ="{{$sfnumber}}" ДатаСчФ="{{$sfdate}}" КодОКВ="643">
                            <СвПрод>
                                   <ИдСв>
                                          <СвЮЛУч НаимОрг="Общество с ограниченной ответственностью &quot;Нордком Карелия&quot;" ИННЮЛ="1001136430" КПП="100101001" />
                                   </ИдСв>
                                   <Адрес>
                                          <АдрРФ Кварт="217" КодРегион="10" Индекс="185014" Город="Петрозаводск" Улица="пр. Лесной" Дом="51" />
                                   </Адрес>
                            </СвПрод>
                            <ГрузОт>
                            <ГрузОтпр>
                            <ИдСв>
                                          <СвЮЛУч НаимОрг="Общество с ограниченной ответственностью &quot;Нордком Карелия&quot;" ИННЮЛ="1001136430" КПП="100101001" />
                                   </ИдСв>
                                   <Адрес>
                                          <АдрРФ Кварт="217" КодРегион="10" Индекс="185014" Город="Петрозаводск" Улица="пр. Лесной" Дом="51" />
                                   </Адрес>
                                   </ГрузОтпр>
                            </ГрузОт>
                            
                            <ГрузПолуч>
                                   <ИдСв>

                                          @if (strlen($inn) === 10)
                                          <СвЮЛУч ИННЮЛ="{{$inn}}" НаимОрг="{!!str_replace('"',' &quot;',$company)!!}" КПП="{{$kpp}}" />
                                          @elseif (strlen($inn) ===12 )
                                          <СвИП ИННФЛ="{{$inn}}">
                                                 <ФИО Фамилия="{{explode(' ',$company)[0]}}" Имя="{{explode(' ',$company)[1]}}" Отчество="{{explode(' ',$company)[2]}}" />
                                          </СвИП>

                                          @endif


                                   </ИдСв>
                                   <Адрес>
                                          <АдрРФ КодРегион="{{$code}}" @if ($FAdressArray[9]!="") Кварт="{{$FAdressArray[9]}}" @endif Индекс="{{$FAdressArray[1]}}" {!!$FAdressArray[4]!="" ?"Город=\"".$FAdressArray[4]."\"":"НаселПункт=\"".$FAdressArray[5]."\""!!}  @if ($FAdressArray[6]!="") Улица="{{$FAdressArray[6]}}" @endif  @if ($FAdressArray[6]!="") Дом="{{$FAdressArray[7]}}" @endif {!!$FAdressArray[8]!="" ?"Корпус=\"".$FAdressArray[8]."\"":""!!} />
                                   </Адрес>
                            </ГрузПолуч>
                            <СвПокуп>
                                   <ИдСв>

                                          @if (strlen($inn) === 10)
                                          <СвЮЛУч ИННЮЛ="{{$inn}}" НаимОрг="{!!str_replace('"',' &quot;',$company)!!}" КПП="{{$kpp}}" />
                                          @elseif (strlen($inn) ===12 )
                                          <СвИП ИННФЛ="{{$inn}}">
                                                 <ФИО Фамилия="{{explode(' ',$company)[0]}}" Имя="{{explode(' ',$company)[1]}}" Отчество="{{explode(' ',$company)[2]}}" />
                                          </СвИП>

                                          @endif


                                   </ИдСв>
                                   <Адрес>
                                   <АдрРФ КодРегион="{{$code}}" @if ($FAdressArray[9]!="") Кварт="{{$FAdressArray[9]}}" @endif Индекс="{{$FAdressArray[1]}}" {!!$FAdressArray[4]!="" ?"Город=\"".$FAdressArray[4]."\"":"НаселПункт=\"".$FAdressArray[5]."\""!!}  @if ($FAdressArray[6]!="") Улица="{{$FAdressArray[6]}}" @endif  @if ($FAdressArray[6]!="") Дом="{{$FAdressArray[7]}}" @endif {!!$FAdressArray[8]!="" ?"Корпус=\"".$FAdressArray[8]."\"":""!!} />
                                   </Адрес>

                            </СвПокуп>
                            <ДокПодтвОтгр НаимДокОтгр="УПД" НомДокОтгр="{{$sfnumber}}" ДатаДокОтгр="{{$sfdate}}" />
                     </СвСчФакт>
                     <ТаблСчФакт>

                            @foreach($goods as $product)


                            <СведТов НомСтр="{{$key++}}" НаимТов="{{$product->name}}" ОКЕИ_Тов="{{substr("0000",1,3-strlen($product->okei)).$product->okei}}" КолТов="{{$product->count}}" ЦенаТов="{{round($product->sm/1.2,2)}}" СтТовБезНДС="{{round($product->sum/1.2,2)}}" НалСт="20%" СтТовУчНал="{{$product->sum}}">
                                   <Акциз>
                                          <БезАкциз>без акциза</БезАкциз>
                                   </Акциз>
                                   <СумНал>
                                          <СумНал>{{round($product->sum-$product->sum/1.2,2)}}</СумНал>
                                   </СумНал>
                                   @if ($product->gtd!="")
                                   <СвТД КодПроисх="{{$product->gtd==""?"-":"156"}}" НомерТД="{{$product->gtd==""?"-":trim($product->gtd)}}" />
                                   @endif
                                   <ДопСведТов НаимЕдИзм="{{$product->okeiname}}" КрНаимСтрПр="{{$product->gtd==""?"-":"Китай"}}" />
                            </СведТов>

                            @endforeach

                            <ВсегоОпл СтТовУчНалВсего="{{$docsum}}" СтТовБезНДСВсего="{{round($docsum/1.2,2)}}">
                                   <СумНалВсего>
                                          <СумНал>{{$docsum-round($docsum/1.2,2)}}</СумНал>
                                   </СумНалВсего>
                            </ВсегоОпл>
                     </ТаблСчФакт>

                     <СвПродПер>
                            <СвПер СодОпер="Отгрузка товаров">
                                   <ОснПер НаимОсн="Без документа-основания" />
                            </СвПер>
                     </СвПродПер>


                     <Подписант ОснПолн="Должностные обязанности" ОблПолн="1" Статус="1">
                            <ЮЛ ИННЮЛ="2649291275" Должн="Старший менеджер по продажам">
                                   <ФИО Фамилия="Сидорихин" Имя="Дмитрий" Отчество="Сергеевич" />
                            </ЮЛ>
                     </Подписант>
              </Документ>
       </Файл>
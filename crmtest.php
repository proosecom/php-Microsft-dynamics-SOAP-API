<?php

require('app/CrmAuth.php');	
require('app/CrmExecuteSoap.php');
require('app/CrmAuthenticationHeader.php');

	function getMsDynamicsUserId($authHeader, $url) {
			$crmexecutesoap = new crmexecutesoap();
			$xml = "<s:Body>";
			$xml .= "<Execute xmlns=\"http://schemas.microsoft.com/xrm/2011/Contracts/Services\">";
			$xml .= "<request i:type=\"c:WhoAmIRequest\" xmlns:b=\"http://schemas.microsoft.com/xrm/2011/Contracts\" xmlns:i=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:c=\"http://schemas.microsoft.com/crm/2011/Contracts\">";
			$xml .= "<b:Parameters xmlns:d=\"http://schemas.datacontract.org/2004/07/System.Collections.Generic\"/>";
			$xml .= "<b:RequestId i:nil=\"true\"/>";
			$xml .= "<b:RequestName>WhoAmI</b:RequestName>";
			$xml .= "</request>";
			$xml .= "</Execute>";
			$xml .= "</s:Body>";
			
			$response = $crmexecutesoap->ExecuteSOAPRequest($authHeader, $xml, $url);
			$responsedom = new DomDocument ();
			$responsedom->loadXML ( $response );
			
			$values = $responsedom->getElementsbyTagName("KeyValuePairOfstringanyType");
			foreach ( $values as $value ) {
				if ($value->firstChild->textContent == "UserId") {
					return $value->lastChild->textContent;
				}
			}		
			return 0;
	   } 
	function CreateContacAndCase($authHeader, $id, $url,$contact, $CrmAuthenticationHeader, $crmauth) {
			$crmexecutesoap = new crmexecutesoap();
			$xml = '<s:Body><Execute xmlns="http://schemas.microsoft.com/xrm/2011/Contracts/Services" xmlns:i="http://www.w3.org/2001/XMLSchema-instance"><request i:type="a:RetrieveMultipleRequest" xmlns:a="http://schemas.microsoft.com/xrm/2011/Contracts"><a:Parameters xmlns:b="http://schemas.datacontract.org/2004/07/System.Collections.Generic"><a:KeyValuePairOfstringanyType><b:key>Query</b:key><b:value i:type="a:QueryExpression"><a:ColumnSet><a:AllColumns>false</a:AllColumns><a:Columns xmlns:c="http://schemas.microsoft.com/2003/10/Serialization/Arrays"><c:string>firstname</c:string></a:Columns></a:ColumnSet><a:Criteria><a:Conditions><a:ConditionExpression><a:AttributeName>emailaddress1</a:AttributeName><a:Operator>Equal</a:Operator><a:Values xmlns:c="http://schemas.microsoft.com/2003/10/Serialization/Arrays"><c:anyType i:type="d:string" xmlns:d="http://www.w3.org/2001/XMLSchema">'.$contact['email'].'</c:anyType></a:Values></a:ConditionExpression></a:Conditions><a:FilterOperator>And</a:FilterOperator><a:Filters /><a:IsQuickFindFilter>false</a:IsQuickFindFilter></a:Criteria><a:Distinct>false</a:Distinct><a:EntityName>contact</a:EntityName><a:LinkEntities /><a:Orders /><a:PageInfo><a:Count>0</a:Count><a:PageNumber>0</a:PageNumber><a:PagingCookie i:nil="true" /><a:ReturnTotalRecordCount>true</a:ReturnTotalRecordCount></a:PageInfo><a:NoLock>false</a:NoLock></b:value></a:KeyValuePairOfstringanyType></a:Parameters><a:RequestId i:nil="true" /><a:RequestName>RetrieveMultiple</a:RequestName></request></Execute></s:Body>';			
							
			$response = $crmexecutesoap->ExecuteSOAPRequest($authHeader, $xml, $url);		
			
			$responsedom = new DomDocument ();
			$responsedom->loadXML($response);		
			$entities = $responsedom->getElementsbyTagName("Entity");
			
			foreach($entities as $entity)
			{	
				$result = $entity->getElementsbyTagName("KeyValuePairOfstringanyType");				
				foreach($result as $value)
				{
					$contactid = $value->textContent;
					$contactid = substr($contactid, 9);
				}
			}		
			file_put_contents('hi.txt',$contactid);
			if($contactid != "")
			{
				$contact_exist = true;
				if( true )
				{	
					$xml = "  <s:Body>";
					$xml .= "    <Execute xmlns=\"http://schemas.microsoft.com/xrm/2011/Contracts/Services\" xmlns:i=\"http://www.w3.org/2001/XMLSchema-instance\">";
					$xml .= "      <request i:type=\"a:CreateRequest\" xmlns:a=\"http://schemas.microsoft.com/xrm/2011/Contracts\">";
					$xml .= "        <a:Parameters xmlns:b=\"http://schemas.datacontract.org/2004/07/System.Collections.Generic\">";
					$xml .= "          <a:KeyValuePairOfstringanyType>";
					$xml .= "            <b:key>Target</b:key>";
					$xml .= "            <b:value i:type=\"a:Entity\">";
					$xml .= "              <a:Attributes>";
					$xml .= "                <a:KeyValuePairOfstringanyType>";
					$xml .= "                  <b:key>subject</b:key>";
					$xml .= "                  <b:value i:type=\"c:string\" xmlns:c=\"http://www.w3.org/2001/XMLSchema\">Live Chat</b:value>";
					$xml .= "                </a:KeyValuePairOfstringanyType>";
					$xml .= "                <a:KeyValuePairOfstringanyType>";
					$xml .= "                  <b:key>regardingobjectid</b:key>";
					$xml .= "                  <b:value i:type=\"a:EntityReference\" ><a:Id>".$contactid."</a:Id><a:LogicalName>contact</a:LogicalName><a:Name i:nil=\"true\"/></b:value>";
					$xml .= "                </a:KeyValuePairOfstringanyType>"; 
					$xml .= "                <a:KeyValuePairOfstringanyType>";
					$xml .= "                  <b:key>description</b:key>";
					$xml .= "                  <b:value i:type=\"c:string\" xmlns:c=\"http://www.w3.org/2001/XMLSchema\">".$contact['description']."</b:value>";
					$xml .= "                </a:KeyValuePairOfstringanyType>";   	
					$xml .= "              </a:Attributes>";
					$xml .= "  <a:EntityState i:nil=\"true\"/>
									<a:FormattedValues xmlns:c=\"http://schemas.datacontract.org/2004/07/System.Collections.Generic\"/>
									<a:Id>".$crmauth->getGUID()."</a:Id>";			
					$xml .= "              <a:LogicalName>task</a:LogicalName>";
					$xml .= "              <a:RelatedEntities />";
					$xml .= "            </b:value>";
					$xml .= "          </a:KeyValuePairOfstringanyType>";
					$xml .= "        </a:Parameters>";
					$xml .= "        <a:RequestId i:nil=\"true\" />";
					$xml .= "        <a:RequestName>Create</a:RequestName>";
					$xml .= "      </request>";
					$xml .= "    </Execute>";
					$xml .= "  </s:Body>";

					$response = $crmexecutesoap->ExecuteSOAPRequest($authHeader, $xml, $url);
				}
			}
			
			
			$xml = "  <s:Body>";
			$xml .= "    <Execute xmlns=\"http://schemas.microsoft.com/xrm/2011/Contracts/Services\" xmlns:i=\"http://www.w3.org/2001/XMLSchema-instance\">";
			$xml .= "      <request i:type=\"a:CreateRequest\" xmlns:a=\"http://schemas.microsoft.com/xrm/2011/Contracts\">";
			$xml .= "        <a:Parameters xmlns:b=\"http://schemas.datacontract.org/2004/07/System.Collections.Generic\">";
			$xml .= "          <a:KeyValuePairOfstringanyType>";
			$xml .= "            <b:key>Target</b:key>";
			$xml .= "            <b:value i:type=\"a:Entity\">";
			$xml .= "              <a:Attributes>";
			$xml .= "                <a:KeyValuePairOfstringanyType>";
			$xml .= "                  <b:key>firstname</b:key>";
			$xml .= "                  <b:value i:type=\"c:string\" xmlns:c=\"http://www.w3.org/2001/XMLSchema\">".$contact['name']."</b:value>";
			$xml .= "                </a:KeyValuePairOfstringanyType>";
			$xml .= "                <a:KeyValuePairOfstringanyType>";
			$xml .= "                  <b:key>emailaddress1</b:key>";
			$xml .= "                  <b:value i:type=\"c:string\" xmlns:c=\"http://www.w3.org/2001/XMLSchema\">".$contact['email']."</b:value>";
			$xml .= "                </a:KeyValuePairOfstringanyType>";


			$xml .= "              </a:Attributes>";
			$xml .= "              <a:LogicalName>contact</a:LogicalName>";
			$xml .= "              <a:RelatedEntities />";
			$xml .= "            </b:value>";
			$xml .= "          </a:KeyValuePairOfstringanyType>";
			$xml .= "        </a:Parameters>";
			$xml .= "        <a:RequestId i:nil=\"true\" />";
			$xml .= "        <a:RequestName>Create</a:RequestName>";
			$xml .= "      </request>";
			$xml .= "    </Execute>";
			$xml .= "  </s:Body>";			
			$response = $crmexecutesoap->ExecuteSOAPRequest($authHeader, $xml, $url);					
			$contact_created = true; 
			
					
			//---------------------------------------------- case start ------------------
			
				if($contact_created == true)
				{
					$xml = '<s:Body><Execute xmlns="http://schemas.microsoft.com/xrm/2011/Contracts/Services" xmlns:i="http://www.w3.org/2001/XMLSchema-instance"><request i:type="a:RetrieveMultipleRequest" xmlns:a="http://schemas.microsoft.com/xrm/2011/Contracts"><a:Parameters xmlns:b="http://schemas.datacontract.org/2004/07/System.Collections.Generic"><a:KeyValuePairOfstringanyType><b:key>Query</b:key><b:value i:type="a:QueryExpression"><a:ColumnSet><a:AllColumns>false</a:AllColumns><a:Columns xmlns:c="http://schemas.microsoft.com/2003/10/Serialization/Arrays"><c:string>firstname</c:string></a:Columns></a:ColumnSet><a:Criteria><a:Conditions><a:ConditionExpression><a:AttributeName>emailaddress1</a:AttributeName><a:Operator>Equal</a:Operator><a:Values xmlns:c="http://schemas.microsoft.com/2003/10/Serialization/Arrays"><c:anyType i:type="d:string" xmlns:d="http://www.w3.org/2001/XMLSchema">'.$contact['email'].'</c:anyType></a:Values></a:ConditionExpression></a:Conditions><a:FilterOperator>And</a:FilterOperator><a:Filters /><a:IsQuickFindFilter>false</a:IsQuickFindFilter></a:Criteria><a:Distinct>false</a:Distinct><a:EntityName>contact</a:EntityName><a:LinkEntities /><a:Orders /><a:PageInfo><a:Count>0</a:Count><a:PageNumber>0</a:PageNumber><a:PagingCookie i:nil="true" /><a:ReturnTotalRecordCount>true</a:ReturnTotalRecordCount></a:PageInfo><a:NoLock>false</a:NoLock></b:value></a:KeyValuePairOfstringanyType></a:Parameters><a:RequestId i:nil="true" /><a:RequestName>RetrieveMultiple</a:RequestName></request></Execute></s:Body>';
					
					$response = $crmexecutesoap->ExecuteSOAPRequest($authHeader, $xml, $url);
					$responsedom = new DomDocument ();
					$responsedom->loadXML ( $response );
					$entities = $responsedom->getElementsbyTagName("Entity");
					foreach($entities as $entity)
					{	
						$result = $entity->getElementsbyTagName("KeyValuePairOfstringanyType");
						foreach($result as $value)
						{
							$contactid = $value->textContent;
							$contactid = substr($contactid, 9);
						}
					}
				}
				
				$xml = "  <s:Body>";
				$xml .= "    <Execute xmlns=\"http://schemas.microsoft.com/xrm/2011/Contracts/Services\" xmlns:i=\"http://www.w3.org/2001/XMLSchema-instance\">";
				$xml .= "      <request i:type=\"a:CreateRequest\" xmlns:a=\"http://schemas.microsoft.com/xrm/2011/Contracts\">";
				$xml .= "        <a:Parameters xmlns:b=\"http://schemas.datacontract.org/2004/07/System.Collections.Generic\">";
				$xml .= "          <a:KeyValuePairOfstringanyType>";
				$xml .= "            <b:key>Target</b:key>";
				$xml .= "            <b:value i:type=\"a:Entity\">";
				$xml .= "              <a:Attributes>";
				
				$xml .= "                <a:KeyValuePairOfstringanyType>";
				$xml .= "                  <b:key>title</b:key>";
				$xml .= "                  <b:value i:type=\"c:string\" xmlns:c=\"http://www.w3.org/2001/XMLSchema\">".$contact['subject']."</b:value>";
				$xml .= "                </a:KeyValuePairOfstringanyType>";
				
				
								
				$xml .= "                <a:KeyValuePairOfstringanyType>";
				$xml .= "                  <b:key>customerid</b:key>";
				$xml .= "                  <b:value i:type=\"a:EntityReference\" ><a:Id>".$contactid."</a:Id><a:LogicalName>contact</a:LogicalName><a:Name i:nil=\"true\"/></b:value>";
				$xml .= "                </a:KeyValuePairOfstringanyType>"; 
				
				
				
				
				
				$xml .= "                <a:KeyValuePairOfstringanyType>";
				$xml .= "                  <b:key>description</b:key>";
				$xml .= "                  <b:value i:type=\"c:string\" xmlns:c=\"http://www.w3.org/2001/XMLSchema\">".$contact['description']."</b:value>";
				$xml .= "                </a:KeyValuePairOfstringanyType>";
				
				
				$xml .= "                <a:KeyValuePairOfstringanyType>";
				$xml .= "                  <b:key>caseorigincode</b:key>";
				$xml .= "                  <b:value i:type=\"a:OptionSetValue\"> <a:Value>2483</a:Value></b:value>";
				$xml .= "                </a:KeyValuePairOfstringanyType>";
				
				$xml .= "                <a:KeyValuePairOfstringanyType>";
				$xml .= "                  <b:key>prioritycode</b:key>";
				$xml .= "                  <b:value i:type=\"a:OptionSetValue\"> <a:Value>3</a:Value></b:value>";
				$xml .= "                </a:KeyValuePairOfstringanyType>";
			

				
				$xml .= "              </a:Attributes>";
				$xml .= "  <a:EntityState i:nil=\"true\"/>
							<a:FormattedValues xmlns:c=\"http://schemas.datacontract.org/2004/07/System.Collections.Generic\"/>
								<a:Id>".$crmauth->getGUID()."</a:Id>";	
				$xml .= "              <a:LogicalName>incident</a:LogicalName>";
				$xml .= "              <a:RelatedEntities />";
				$xml .= "            </b:value>";
				$xml .= "          </a:KeyValuePairOfstringanyType>";
				
								
				
				
				
				
				
				$xml .= "        </a:Parameters>";
				$xml .= "        <a:RequestId i:nil=\"true\" />";
				$xml .= "        <a:RequestName>Create</a:RequestName>";
				$xml .= "      </request>";
				$xml .= "    </Execute>";
				$xml .= "  </s:Body>"; 
				
				
				
				$response = $crmexecutesoap->ExecuteSOAPRequest( $authHeader, $xml, $url );
				//echo "<pre>";
				print_r($response); die;
				
				if($response){
				 return true;  	
				}else{
					return false;
				}				
				
		}


$data = array();	
$data['priority'] =  2;
$data['subject'] = "Test case with low priority";
$data['description'] = "Test case";
$data['name'] = "Someone";
$data['email'] = "someone@example.com";
$data['caseorigincode'] = 1;


$CrmAuthenticationHeader = new CrmAuthenticationHeader();
$CrmAuth = new CrmAuth($CrmAuthenticationHeader);

$uname = "" //Dynamic CRM user name or email;
$pass = "" //Password of account;				
$msdynamics_url = "" //Post login Dynamic URL;	
$authHeader = $CrmAuth->GetHeaderOnline($uname, $pass, $msdynamics_url);

			
$userid = getMsDynamicsUserId($authHeader, $msdynamics_url);		
$result = CreateContacAndCase($authHeader,$userid, $msdynamics_url, $data, $CrmAuthenticationHeader, $CrmAuth);
$$response = "";
if($result){				
	$response = $this->responseArray(true,true,"Successfully created Microsoft Dynamics case!",[],['ms_dynamics_case' => [$result]]);
}else{				
	$response = $this->responseArray(false,true,"Something went wrong. Unable to create Microsoft Dynamics case!",[],['ms_dynamics_case' =>[]]);
}
echo $response;
?>

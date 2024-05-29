<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_qos_limit";
$MY_MSG_FILE  = "adv_qos.php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "adv_qos_limit";
set("/runtime/web/help_page",$MY_NAME);
/* --------------------------------------------------------------------------- */
if($ACTION_POST != "")
{
	require("/www/model/__admin_check.php");
	require("/www/__action_adv.php");
	$ACTION_POST = "";
	exit;
}

/* --------------------------------------------------------------------------- */
require("/www/model/__html_head.php");
require("/www/comm/__js_ip.php");
set("/runtime/web/next_page",$first_frame);
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.
echo "<!--debug\n";
$cfg_band = query("/wlan/ch_mode");
if($cfg_band == 0) // 11g
{
	echo "anchor 11g";
	anchor("/wlan/inf:1");
}
else
{
	echo "anchor 11a";
	//anchor("/wlan/inf:2");
	anchor("/wlan/inf:1");
}
$cfg_mode = query("ap_mode");
$cfg_ipv6 = query("/inet/entry:1/ipv6/valid");
$cfg_assoc_enable = query("/wlan/inf:1/userlimit:0/enable");
$cfg_pri_assoc = query("/wlan/inf:1/userlimit:0/number");
$cfg_m1_assoc = query("/wlan/inf:1/userlimit:1/number");
$cfg_m2_assoc = query("/wlan/inf:1/userlimit:2/number");
$cfg_m3_assoc = query("/wlan/inf:1/userlimit:3/number");
$cfg_m4_assoc = query("/wlan/inf:1/userlimit:4/number");
$cfg_m5_assoc = query("/wlan/inf:1/userlimit:5/number");
$cfg_m6_assoc = query("/wlan/inf:1/userlimit:6/number");
$cfg_m7_assoc = query("/wlan/inf:1/userlimit:7/number");
$cfg_flow_enable = query("/trafficctrl/wtp/trafficmgr/enable");
$cfg_pri_flow = query("/trafficctrl/trafficrule:1/maxwidth");
$cfg_m1_flow = query("/trafficctrl/trafficrule:2/maxwidth");
$cfg_m2_flow = query("/trafficctrl/trafficrule:3/maxwidth");
$cfg_m3_flow = query("/trafficctrl/trafficrule:4/maxwidth");
$cfg_m4_flow = query("/trafficctrl/trafficrule:5/maxwidth");
$cfg_m5_flow = query("/trafficctrl/trafficrule:6/maxwidth");
$cfg_m6_flow = query("/trafficctrl/trafficrule:7/maxwidth");
$cfg_m7_flow = query("/trafficctrl/trafficrule:8/maxwidth");
anchor("/qos");
$cfg_qos_enable = query("enable");
$cfg_qos_type = query("qostype");
$cfg_http_enable = query("classifiers/http");
$cfg_automatic_enable = query("classifiers/auto");
anchor("/qos/rule");


echo "-->";
/* --------------------------------------------------------------------------- */
?>

<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>
function Tablename(tab)
{
	var str = "";
	var name = "";
	
		name = tab;

	for(var i=0; i < name.length; i++)
	{
		if(i!=0 && (i%11)==0)// change line
		{
			str+="<br \>";	
		}		
		str+=name.charAt(i);
	}
	return(str);	
}

function on_change_pro()
{
	var f=get_obj("frm");

	f.protocol.disabled = false;
	f.host_1_port_s.disabled = false;
	f.host_1_port_e.disabled = false;
	f.host_2_port_s.disabled = false;
	f.host_2_port_e.disabled = false;

	if(f.protocol_select.value == "0") //Any
	{
		f.protocol.value = "256";
		f.protocol.disabled = true;
		f.host_1_port_s.disabled = true;
		f.host_1_port_e.disabled = true;
		f.host_2_port_s.disabled = true;
		f.host_2_port_e.disabled = true;
	}
	else if(f.protocol_select.value == "1") //TCP
	{
		f.protocol.value = "6";
		f.protocol.disabled = true;
	}
	else if(f.protocol_select.value == "2") //UDP
	{
		f.protocol.value = "17";
		f.protocol.disabled = true;
	}
	else if(f.protocol_select.value == "3") //Both
	{
		f.protocol.value = "257";
		f.protocol.disabled = true;
	}
	else if(f.protocol_select.value == "4") //ICMP
	{
		f.protocol.value = "1";
		f.protocol.disabled = true;
		f.host_1_port_s.disabled = true;
		f.host_1_port_e.disabled = true;
		f.host_2_port_s.disabled = true;
		f.host_2_port_e.disabled = true;
	}
	else //other
	{
		f.host_1_port_s.disabled = true;
		f.host_1_port_e.disabled = true;
		f.host_2_port_s.disabled = true;
		f.host_2_port_e.disabled = true;
	}
}

function on_change_scan_table_height()
{
	var x = get_obj("acl_tab").offsetHeight;
	
	if(get_obj("adjust_td") != null)
	{
		if(x <= 120)
			get_obj("adjust_td").width="50";
		else
			get_obj("adjust_td").width="30";
	}
}

function print_rule_del(id)
{
	var str="";

	str+="<a href='javascript:rule_del_confirm(\""+id+"\")'><img src='/pic/delete.jpg' border=0></a>";

	//document.write(str);
	return (str);
}


function print_rule_edit(id)
{
	var str="";

	str+="<a href='javascript:rule_edit_confirm(\""+id+"\")'><img src='/pic/edit.jpg' border=0></a>";

	//document.write(str);
	return (str);
}

var qos_list=[['index','name','h_rule_state','h_rule_priority','h_rule_protocol','h_rule_protocol_select','h_host_1_ip_s','h_host_1_ip_e','h_host_2_ip_s','rule_host_2_ip_e','rule_host_1_port_s','rule_host_1_port_e','rule_host_2_port_s','rule_host_2_port_e','priority']
<?
$tmp_rule_qos = 0;
for("/qos/rule/index")
{
	
	$tmp_rule_qos ++;
	
	$rule_priority = query("priority");
	if($rule_priority == "0"){$rule_priority = $m_r_vo;}
	else if($rule_priority == "1"){$rule_priority = $m_r_vi;}
	else if($rule_priority == "2"){$rule_priority = $m_r_be;}
	else {$rule_priority = $m_r_bk;}
	$rule_host_1_ip_s =query("host1/startip");
	$rule_host_1_ip_e =query("host1/endip");
	$rule_host_2_ip_s =query("host2/startip");
	$rule_host_2_ip_e =query("host2/endip");
	$rule_protocol = query("protocoltype");
	if($rule_protocol == "0"){$rule_prrule_protocoliority = $m_any;}
	else if($rule_protocol == "1"){$rule_prrule_protocoliority = $m_tcp;}
	else if($rule_protocol == "2"){$rule_prrule_protocoliority = $m_udp;}
	else if($rule_protocol == "3"){$rule_prrule_protocoliority = $m_both;}
	else if($rule_protocol == "4"){$rule_prrule_protocoliority = $m_icmp;}
	else {$rule_prrule_protocoliority = $m_other;}
	$rule_host_1_port_s =query("host1/startport");
	$rule_host_1_port_e =query("host1/endport");
	$rule_host_2_port_s =query("host2/startport");
	$rule_host_2_port_e =query("host2/endport");
	$cfg_rule_state = query("state");
	echo ",\n['".$@."','".get("j","name")."','".$cfg_rule_state."','".$rule_priority."','".query("protocol")."','".$rule_protocol."','".$rule_host_1_ip_s."','".$rule_host_1_ip_e."','".$rule_host_2_ip_s."','".$rule_host_2_ip_e."','".$rule_host_1_port_s."','".$rule_host_1_port_e."','".$rule_host_2_port_s."','".$rule_host_2_port_e."','".query("priority")."']";
}
?>];
var all_index=parseInt("<?=$tmp_rule_qos?>");
function rule_del_confirm(id)
{
	var f=get_obj("frm")
	var f_final=get_obj("final_form");
	var ssid=qos_list[id][1];
	if(confirm("<?=$a_rule_del_confirm?>"+ssid+" ?")==false) return;
	f_final.f_rule_del.value = id;
	fields_disabled(f, false);
	get_obj("final_form").submit();
}

function rule_edit_confirm(id)
{
	var f=get_obj("frm")
	var f_final=get_obj("final_form");
	f_final.rule_edit.value = id;
	init();
}

function on_change_type()
{
	var f=get_obj("frm");
	var f_final=get_obj("final_form");
	get_obj("byssid_part").style.display = "none";
	get_obj("bysta_part").style.display = "none";
	if(f.qos_type.value == 0)
	{
		f_final.f_qos_type.value = 0;
		get_obj("byssid_part").style.display = "";
		on_click_assoc();
		on_click_flow();
	}
	else
	{
		f_final.f_qos_type.value = 1;
		get_obj("bysta_part").style.display = "";
		on_change_enable(f.qos_enable);
	}
	AdjustHeight();
}

function on_change_enable(s)
{
	var f=get_obj("frm");
	if(s.value == 1)
	{
		f.http.disabled = f.auto.disabled = f.b_add.disabled = f.b_cancel.disabled =
		f.name.disabled = f.protocol.disabled = f.protocol_select.disabled = f.priority.disabled = 
    	f.host_1_ip_s.disabled = f.host_1_ip_e.disabled = f.host_1_port_s.disabled = f.host_1_port_e.disabled =
    	f.host_2_ip_s.disabled = f.host_2_ip_e.disabled = f.host_2_port_s.disabled = f.host_2_port_e.disabled = false;
		for(var s=1; s<qos_list.length; s++)
		{
			get_obj("rule_state"+s).disabled = false;
		}
	}
	else
	{
		f.http.disabled = f.auto.disabled = f.b_add.disabled = f.b_cancel.disabled =
		f.name.disabled = f.protocol.disabled = f.protocol_select.disabled = f.priority.disabled =
        f.host_1_ip_s.disabled = f.host_1_ip_e.disabled = f.host_1_port_s.disabled = f.host_1_port_e.disabled =
        f.host_2_ip_s.disabled = f.host_2_ip_e.disabled = f.host_2_port_s.disabled = f.host_2_port_e.disabled = true;	
		for(var s=1; s<qos_list.length; s++)
        {
            get_obj("rule_state"+s).disabled = true;
        }
	}

}

function on_click_assoc()
{
	var f=get_obj("frm");
	var f_final=get_obj("final_form");
	if(f.assoc_enable.checked)
	{
		f_final.f_assoc_enable.value = 1;
		f.pri_assoc.disabled = false;	
		for(var i=1; i<8; i++)
		{
			get_obj("m"+i+"_assoc").disabled = false;
		}
	}
	else
	{
		f_final.f_assoc_enable.value = 0;
		f.pri_assoc.disabled = true;
		for(var i=1; i<8; i++)
		{
			get_obj("m"+i+"_assoc").disabled = true;
		}
	}
}

function on_click_flow()
{
	var f=get_obj("frm");
	var f_final=get_obj("final_form");
	if(f.flow_enable.checked)
    {
		f_final.f_flow_enable.value = 1;
        f.pri_flow.disabled = false;
        for(var i=1; i<8; i++)
        {
            get_obj("m"+i+"_flow").disabled = false;
        }
    }
    else
    {
		f_final.f_flow_enable.value = 0;
        f.pri_flow.disabled = true;
        for(var i=1; i<8; i++)
        {
            get_obj("m"+i+"_flow").disabled = true;
        }
    }
}

function do_cancel()
{
	var f=get_obj("frm");
	f.name.value = f.protocol.value = "";
	f.host_1_ip_s.value = f.host_1_ip_e.value = f.host_1_port_s.value = f.host_1_port_e.value =
	f.host_2_ip_s.value = f.host_2_ip_e.value = f.host_2_port_s.value = f.host_2_port_e.value = "";
	f.protocol_select.value = 0;
	f.priority.value = 3;
//	self.location.href="<?=$MY_NAME?>.php?qos_reload=1&qos_type=1";
}
/* page init functoin */
function init()
{
	var f=get_obj("frm");
	var f_final=get_obj("final_form");
	var rule_index = f_final.rule_edit.value;
	var add_edit_title_obj = get_obj("add_edit_title");

	select_index(f.qos_enable, "<?=$cfg_qos_enable?>");
	select_index(f.qos_type, "<?=$cfg_qos_type?>");
	f.http.checked = <? if ($cfg_http_enable=="1") {echo "true";} else {echo "false";}?>;
	f.auto.checked = <? if ($cfg_automatic_enable=="1") {echo "true";} else {echo "false";}?>;

	if(f_final.rule_edit.value != "")
	{
		add_edit_title_obj.innerHTML = "<?=$m_edit_title?>";

		f.name.value = qos_list[rule_index][1];
		select_index(f.priority, qos_list[rule_index][14]);
		f.protocol.value = qos_list[rule_index][4];
		select_index(f.protocol_select, qos_list[rule_index][5]);
		f.host_1_ip_s.value = qos_list[rule_index][6];
		f.host_1_ip_e.value = qos_list[rule_index][7];
		f.host_2_ip_s.value = qos_list[rule_index][8];
		f.host_2_ip_e.value = qos_list[rule_index][9];
		f.host_1_port_s.value = qos_list[rule_index][10];
		f.host_1_port_e.value = qos_list[rule_index][11];
		f.host_2_port_s.value = qos_list[rule_index][12];
		f.host_2_port_e.value = qos_list[rule_index][13];
		
	}
	
	if ("<?=$cfg_mode?>"==1 || "<?=$cfg_mode?>"==2 )
	{
			f.qos_enable.disabled=true;
	}
	if("<?=$cfg_ipv6?>" == "1")
    {
        f.qos_enable.disabled=true;
        f.qos_enable.value = 0;
    }

    	
	on_change_type();
	AdjustHeight();
}

/* parameter checking */
function check()
{
	var f=get_obj("frm");	
	var f_final=get_obj("final_form");	
	var qos_index=1;
	var tmp_state = 0;
	
	if(f.qos_type.value == 0)
	{
		if(f.assoc_enable.checked)
		{
			if(is_blank(f.pri_assoc.value))
			{
				alert("<?=$a_empty_assoc_num?>");
				f.pri_assoc.focus();
				return false;
			}
			var j=0;
            for(var m=0;m<get_obj("pri_assoc").value.length;m++)
            {
                if(get_obj("pri_assoc").value.charAt(m)!=0)
                {
                    get_obj("pri_assoc").value=get_obj("pri_assoc").value.substring(m);
                    break;
                }
                else
                {
                    j++;
                    if(j == get_obj("pri_assoc").value.length)
                    {
                        get_obj("pri_assoc").value=0;
                        break;
                    }
                }
            }
			if(!is_in_range(f.pri_assoc.value,0,64))
			{
				alert("<?=$a_invalid_assoc_num?>");
				field_select(f.pri_assoc);
				return false;
			}
			for(var i=1; i<8; i++)
			{
				if(is_blank(get_obj("m"+i+"_assoc").value))
				{
					alert("<?=$a_empty_assoc_num?>");
					get_obj("m"+i+"_assoc").focus();
					return false;
				}
				var j=0;
                for(var m=0;m<get_obj("m"+i+"_assoc").value.length;m++)
                {
                    if(get_obj("m"+i+"_assoc").value.charAt(m)!=0)
                    {
                        get_obj("m"+i+"_assoc").value=get_obj("m"+i+"_assoc").value.substring(m);
                        break;
                    }
                    else
                    {
                        j++;
                        if(j == get_obj("m"+i+"_assoc").value.length)
                        {
                            get_obj("m"+i+"_assoc").value=0;
                            break;
                        }
                    }
                }
				if(!is_in_range(get_obj("m"+i+"_assoc").value,0,64))
				{
					alert("<?=$a_invalid_assoc_num?>");
					field_select(get_obj("m"+i+"_assoc"));
					return false;
				}
			}
		}
		if(f.flow_enable.checked)
		{
			if(f.pri_flow.value != "")
			{
				var j=0;
                for(var m=0;m<get_obj("pri_flow").value.length;m++)
                {
                    if(get_obj("pri_flow").value.charAt(m)!=0)
                    {
                        get_obj("pri_flow").value=get_obj("pri_flow").value.substring(m);
                        break;
                    }
                    else
                    {
                        j++;
                        if(j == get_obj("pri_flow").value.length)
                        {
                            get_obj("pri_flow").value=0;
                            break;
                        }
                    }
                }
				if(!is_in_range(f.pri_flow.value,1,150000))
				{
					alert("<?=$a_invalid_flow_num?>");
					field_select(f.pri_flow);
					return false;
				}
			}
			for(var i=1; i<8; i++)
			{
				if(get_obj("m"+i+"_flow").value != "")
				{
					var j=0;
                    for(var m=0;m<get_obj("m"+i+"_flow").value.length;m++)
                    {
                        if(get_obj("m"+i+"_flow").value.charAt(m)!=0)
                        {
                            get_obj("m"+i+"_flow").value=get_obj("m"+i+"_flow").value.substring(m);
                            break;
                        }
                        else
                        {
                            j++;
                            if(j == get_obj("m"+i+"_flow").value.length)
                            {
                                get_obj("m"+i+"_flow").value=0;
                                break;
                            }
                        }
                    }
					if(!is_in_range(get_obj("m"+i+"_flow").value,1,150000))
					{
						alert("<?=$a_invalid_flow_num?>");
						field_select(get_obj("m"+i+"_flow"));
						return false;
					}
				}
			}
		}
	}
	else if(f.qos_type.value == 1)
	{
		f_final.f_qos_enable.value = 0;
	    if(f.qos_enable.value == 1)
    	{
        	f_final.f_qos_enable.value = 1;
	    }
		if(f_final.f_qos_enable.value == 1)
		{
			if(f.http.checked == false && f.auto.checked == false)
			{
				for(var i=1 ; i<="<?=$tmp_rule_qos?>" ; i++)
				{
					if(get_obj("rule_state"+i).checked == true)
					{
						tmp_state =1;	
					}
				}
				if(tmp_state != 1)
				{
					alert("<?=$a_empty_rule?>");	
				}
			}
			fields_disabled(f, false);
			f_final.f_qos_enable.value=f.qos_enable.value;
			if(f.http.checked)
			{
				f_final.http.value=1;
			}
			else
			{
				f_final.http.value=0;
			}
			if(f.auto.checked)
			{
				f_final.auto.value=1;
			}
			else
			{
				f_final.auto.value=0;
			}	
			while(qos_index <= all_index)
			{
				if(get_obj("rule_state"+qos_index).checked)
				{
					get_obj("f_rule_state"+qos_index).value=1;
				}
				else
				{
					get_obj("f_rule_state"+qos_index).value=0;
				}
				qos_index++;
			}
		}
	}
	f_final.pri_assoc.value = f.pri_assoc.value;
	f_final.m1_assoc.value = f.m1_assoc.value;
	f_final.m2_assoc.value = f.m2_assoc.value;
	f_final.m3_assoc.value = f.m3_assoc.value;
	f_final.m4_assoc.value = f.m4_assoc.value;
	f_final.m5_assoc.value = f.m5_assoc.value;
	f_final.m6_assoc.value = f.m6_assoc.value;
	f_final.m7_assoc.value = f.m7_assoc.value;
	f_final.pri_flow.value = f.pri_flow.value;
    f_final.m1_flow.value = f.m1_flow.value;
    f_final.m2_flow.value = f.m2_flow.value;
    f_final.m3_flow.value = f.m3_flow.value;
    f_final.m4_flow.value = f.m4_flow.value;
    f_final.m5_flow.value = f.m5_flow.value;
    f_final.m6_flow.value = f.m6_flow.value;
    f_final.m7_flow.value = f.m7_flow.value;
	//get_obj("final_form").submit();
	return true;
}

function submit()
{
	var f_final=get_obj("final_form");	
	var f=get_obj("frm");		
	if(check()) 
	{
		f_final.submit();
	}	
}

function ip1place()
{
	var f_final=get_obj("final_form");	
	var f=get_obj("frm");
	var ip_s, ip_e;	
	if (is_valid_ip3(f.host_1_ip_s.value, 0)==false)
		{
			alert("<?=$a_invalid_ip?>");
			field_focus(f.host_1_ip_s, "**");
			return false;
		}
	if(f.host_1_ip_e.value !="")
	{
		if(is_valid_ip3(f.host_1_ip_e.value, 0)==false)
		{
			alert("<?=$a_invalid_ip?>");
			field_focus(f.host_1_ip_e, "**");
			return false;
		}
	}
	else
	{
		f.host_1_ip_e.value=f.host_1_ip_s.value;
	}	
	ip_s = get_ip(f.host_1_ip_s.value);
	ip_e = get_ip(f.host_1_ip_e.value);

	if((ip_s[1] != ip_e[1]) || (ip_s[2] != ip_e[2]) || (ip_s[3] != ip_e[3]))
	{
		alert("<?=$a_invalid_ip?>");
		field_focus(f.host_1_ip_e, "**");
		return false;
	}
	
	f_final.f_host_1_ip_r.value = ip_e[4] - ip_s[4];
	f_final.f_host_1_ip_r.value = parseInt(f_final.f_host_1_ip_r.value, [10]) + 1;

	if(f_final.f_host_1_ip_r.value < 1)
	{
		alert("<?=$a_invalid_ip_range?>");
		field_focus(f.host_1_ip_e, "**");
		return false;
	}
}
function port1place()
{
	var f_final=get_obj("final_form");	
	var f=get_obj("frm");
	var ip_s, ip_e;	
	
	if (!is_valid_port_str(f.host_1_port_s.value))
	{
		alert("<?=$a_invalid_port?>");
		field_focus(f.host_1_port_s, "**");
		return false;
	}
	
	if(f.host_1_port_e.value=="" && f.host_1_port_s.value!="")
	{
		f.host_1_port_e.value=f.host_1_port_s.value;
	}
	
	if (!is_valid_port_str(f.host_1_port_e.value))
	{
		alert("<?=$a_invalid_port?>");
		field_focus(f.host_1_port_e, "**");
		return false;
	}
	
	f_final.f_host_1_port_r.value = eval(f.host_1_port_e.value) - eval(f.host_1_port_s.value);
	f_final.f_host_1_port_r.value = parseInt(f_final.f_host_1_port_r.value, [10]) + 1;
	
	if(f_final.f_host_1_port_r.value < 1)
	{
		alert("<?=$a_invalid_port_range?>");
		field_focus(f.host_1_port_e, "**");
				return false;
	}
}

function ip2place()
{
	var f_final=get_obj("final_form");	
	var f=get_obj("frm");
	var ip_s, ip_e;	
	
	if (is_valid_ip3(f.host_2_ip_s.value, 0)==false)
	{
		alert("<?=$a_invalid_ip?>");
		field_focus(f.host_2_ip_s, "**");
		return false;
	}
	
	if(f.host_2_ip_e.value !="")
	{
		if(is_valid_ip3(f.host_2_ip_e.value, 0)==false)
		{
			alert("<?=$a_invalid_ip?>");
			field_focus(f.host_2_ip_e, "**");
			return false;
		}
	}
	else
	{
		f.host_2_ip_e.value=f.host_2_ip_s.value;
	}	
	
	ip_s = get_ip(f.host_2_ip_s.value);
	ip_e = get_ip(f.host_2_ip_e.value);

	if((ip_s[1] != ip_e[1]) || (ip_s[2] != ip_e[2]) || (ip_s[3] != ip_e[3]))
	{
		alert("<?=$a_invalid_ip?>");
		field_focus(f.host_2_ip_e, "**");
		return false;
	}
	
	f_final.f_host_2_ip_r.value = ip_e[4] - ip_s[4];
	f_final.f_host_2_ip_r.value = parseInt(f_final.f_host_2_ip_r.value, [10]) + 1;

	if(f_final.f_host_2_ip_r.value < 1)
	{
		alert("<?=$a_invalid_ip_range?>");
		field_focus(f.host_2_ip_e, "**");
		return false;
	}
}
function port2place()
{
	var f_final=get_obj("final_form");	
	var f=get_obj("frm");
	var ip_s, ip_e;	
	
	if (!is_valid_port_str(f.host_2_port_s.value))
	{
		alert("<?=$a_invalid_port?>");
		field_focus(f.host_2_port_s, "**");
		return false;
	}
	
	if(f.host_2_port_e.value=="" && f.host_2_port_s.value!="")
	{
		f.host_2_port_e.value=f.host_2_port_s.value;
	}
	
	if (!is_valid_port_str(f.host_2_port_e.value))
	{
		alert("<?=$a_invalid_port?>");
		field_focus(f.host_2_port_e, "**");
		return false;
	}
	
	f_final.f_host_2_port_r.value = eval(f.host_2_port_e.value) - eval(f.host_2_port_s.value);
	f_final.f_host_2_port_r.value = parseInt(f_final.f_host_2_port_r.value, [10]) + 1;
	
	if(f_final.f_host_2_port_r.value < 1)
	{
		alert("<?=$a_invalid_port_range?>");
		field_focus(f.host_2_port_e, "**");
		return false;
	}
}

function do_add()
{
	var f=get_obj("frm");	
	var f_final=get_obj("final_form");	
	var qos_index=1;
	var ip_s, ip_e;
		
	if("<?=$tmp_rule_qos?>" >= 64)
	{
		alert("<?=$a_max_qos_rule?>");
		f.name.select();
		return false;			
	}
			
	if(!is_blank(f.name.value))
	{		
		if(first_blank(f.name.value))
		{
			alert("<?=$a_first_blank_name?>");
			f.name.select();
			return false;
		}

		if(strchk_unicode(f.name.value))
		{
			alert("<?=$a_invalid_name?>");
			f.name.select();
			return false;
		}
	}	
	else
	{
		alert("<?=$a_empty_name?>");
		f.name.focus();
		return false;
	}
	
	
	if (!is_valid_port_str(f.protocol.value))
	{
		alert("<?=$a_invalid_port?>");
		field_focus(f.protocol, "**");
		return false;
	}	
		
	if ((f.host_1_ip_s.value!="" || f.host_1_port_s.value!="") &&(f.host_2_ip_s.value=="" && f.host_2_port_s.value==""))
	{	
		if(f.host_1_ip_s.value=="" && f.host_1_port_s.value!="")
		{
			if(port1place()==false)
			return false;
		}
		else if(f.host_1_ip_s.value=="" && f.host_1_port_s.value=="")
		{
			if(ip1place()==false)
			return false;
		}
		else if(f.host_1_ip_s.value!="" && f.host_1_port_s.value=="")
		{	
			if(ip1place()==false)		
			return false;
		}
		else if(f.host_1_ip_s.value!="" && f.host_1_port_s.value!="" && f.host_1_ip_s.value!="")
		{
			if(ip1place()==false)
			return false;
			if(port1place()==false)
			return false;
		}
	}
	
	else if((f.host_1_ip_s.value!="" || f.host_1_port_s.value!="") &&(f.host_2_ip_s.value!="" || f.host_2_port_s.value!=""))
	{
		if(f.host_1_ip_s.value=="" && f.host_1_port_s.value!="")
		{
			if ( f.host_2_port_s.value=="")
			{
				if(port1place()==false)
				return false;
				if(ip2place()==false)
				return false;
			}
			else
			{
				if(port1place()==false)
				return false;
				if(port2place()==false)
				return false;
			}
		}
		else if(f.host_1_ip_s.value!="" && f.host_1_port_s.value!="") 
		{	
			if(ip1place()==false)
				return false;
			if(port1place()==false)
				return false;		
			if(f.host_2_ip_s.value!="" && f.host_2_port_s.value!="")
			{

				if(ip2place()==false)
					return false;		
				if(port2place()==false)
					return false;			
			}
			else if (f.host_2_port_s.value!="")
			{
				if(port2place()==false)
				return false;
				
			}	
			else if(f.host_2_ip_s.value!="")
			{
				if(ip2place()==false)
					return false;			
			}
		}
		else
		{
			if ( f.host_2_ip_s.value=="")
			{
				if(ip1place()==false)
				return false;
				if(port2place()==false)
				return false;
			}
			else
			{
				if(ip1place()==false)
				return false;
				if(ip2place()==false)
				return false;
			}
		}
	}
	
	else
	{
		if(ip1place()==false)
		return false;
	}
	
	f_final.f_qos_enable.value = 0;
	if(f.qos_enable.value == 1)
	{
		f_final.f_qos_enable.value = 1;	
	}
			
	f_final.f_add.value = 1;
	fields_disabled(f, false);
	f_final.f_qos_enable.value=f.qos_enable.value;
	if(f.http.checked)
	{
		f_final.http.value=1;
	}
	else
	{
		f_final.http.value=0;
	}
	if(f.auto.checked)
	{
		f_final.auto.value=1;
	}
	else
	{
		f_final.auto.value=0;
	}
	f_final.name.value=f.name.value;
	f_final.priority.value=f.priority.value;
	f_final.protocol_select.value=f.protocol_select.value;
	f_final.protocol.value=f.protocol.value;
	f_final.host_1_ip_s.value=f.host_1_ip_s.value;
	f_final.host_1_ip_e.value=f.host_1_ip_e.value;
	f_final.host_1_port_s.value=f.host_1_port_s.value;
	f_final.host_1_port_e.value=f.host_1_port_e.value;
	f_final.host_2_ip_s.value=f.host_2_ip_s.value;
	f_final.host_2_ip_e.value=f.host_2_ip_e.value;
	f_final.host_2_port_s.value=f.host_2_port_s.value;
	f_final.host_2_port_e.value=f.host_2_port_e.value;
	
	while(qos_index <= all_index)
	{
		if(get_obj("rule_state"+qos_index).checked)
		{
			get_obj("f_rule_state"+qos_index).value=1;
		}
		else
		{
			get_obj("f_rule_state"+qos_index).value=0;
		}
		qos_index++;
	}
	get_obj("final_form").submit();
}
</script>

<body <?=$G_BODY_ATTR?> onload="init();">

<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="return false;">
<table id="table_frame" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?>>
	<tr>
		<td valign="top">
			<table id="table_header" <?=$G_TABLE_ATTR_CELL_ZERO?>>
			<tr>
				<td id="td_header" valign="middle"><?=$m_context_title?></td>
			</tr>
			</table>
<!-- ________________________________ Main Content Start ______________________________ -->
			<table id="table_set_main"  border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
				<tr>
					<td width="25%" id="td_left">
						<?=$m_qos_type?>
					</td>
					<td id="td_right">&nbsp;&nbsp;
						<?=$G_TAG_SCRIPT_START?>genSelect("qos_type", [0,1], ["<?=$m_by_ssid?>","<?=$m_by_sta?>"], "on_change_type()");<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>
				<tbody id="byssid_part" style="display:none;">
				<tr>
					<td colspan="2">
						<table class="table_tool" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
							<tr>
								<td class="table_tool_td" valign="middle" colspan="4"><b><?=$m_assoc_title?></b></td>
							</tr>
							<tr>
                                <td width="25%">
                                    <?=$m_assoc_enable?>
                                </td>
                                <td id="td_right">&nbsp;&nbsp;
									<input type="checkbox" name="assoc_enable" id="assoc_enable" onclick="on_click_assoc()" <? if($cfg_assoc_enable == 1) {echo "checked";}?>>
                                </td>
                            </tr>
							<tr>
								<td width="25%" id="td_left"><?=$m_pri?></td>
								<td><input type="text" name="pri_assoc" id="pri_assoc" value="<?=$cfg_pri_assoc?>" size="8" maxlength="4"></td>
							</tr>
							<tr>
                                <td width="25%" id="td_left"><?=$m_m1?></td>
                                <td><input type="text" name="m1_assoc" id="m1_assoc" value="<?=$cfg_m1_assoc?>" size="8" maxlength="4"></td>
                                <td width="25%" id="td_left"><?=$m_m2?></td>
                                <td><input type="text" name="m2_assoc" id="m2_assoc" value="<?=$cfg_m2_assoc?>" size="8" maxlength="4"></td>
                            </tr>
							<tr>
                                <td width="25%" id="td_left"><?=$m_m3?></td>
                                <td><input type="text" name="m3_assoc" id="m3_assoc" value="<?=$cfg_m3_assoc?>" size="8" maxlength="4"></td>
                                <td width="25%" id="td_left"><?=$m_m4?></td>
                                <td><input type="text" name="m4_assoc" id="m4_assoc" value="<?=$cfg_m4_assoc?>" size="8" maxlength="4"></td>
                            </tr>
							<tr>
                                <td width="25%" id="td_left"><?=$m_m5?></td>
                                <td><input type="text" name="m5_assoc" id="m5_assoc" value="<?=$cfg_m5_assoc?>" size="8" maxlength="4"></td>
                                <td width="25%" id="td_left"><?=$m_m6?></td>
                                <td><input type="text" name="m6_assoc" id="m6_assoc" value="<?=$cfg_m6_assoc?>" size="8" maxlength="4"></td>
                            </tr>
							<tr>
                                <td width="25%" id="td_left"><?=$m_m7?></td>
                                <td><input type="text" name="m7_assoc" id="m7_assoc" value="<?=$cfg_m7_assoc?>" size="8" maxlength="4"></td>
                            </tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<table class="table_tool" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
							<tr>
								<td class="table_tool_td" valign="middle" colspan="4"><b><?=$m_flow_title?></b></td>
							</tr>
							<tr>
                                <td width="25%">
                                    <?=$m_flow_enable?>
                                </td>
                                <td id="td_right">&nbsp;&nbsp;
									<input type="checkbox" name="flow_enable" id="flow_enable" onclick="on_click_flow()" <? if($cfg_flow_enable == 1) {echo "checked";}?>>
                                </td>
                            </tr>
							<tr>
								<td width="25%" id="td_left"><?=$m_pri?></td>
                                <td><input type="text" name="pri_flow" id="pri_flow" value="<?=$cfg_pri_flow?>" size="8" maxlength="6">kbits/sec</td>
							</tr>
							<tr>
                                <td width="25%" id="td_left"><?=$m_m1?></td>
                                <td><input type="text" name="m1_flow" id="m1_flow" value="<?=$cfg_m1_flow?>" size="8" maxlength="6">kbits/sec</td>
                                <td width="25%" id="td_left"><?=$m_m2?></td>
                                <td><input type="text" name="m2_flow" id="m2_flow" value="<?=$cfg_m2_flow?>" size="8" maxlength="6">kbits/sec</td>
                            </tr>
							<tr>
                                <td width="25%" id="td_left"><?=$m_m3?></td>
                                <td><input type="text" name="m3_flow" id="m3_flow" value="<?=$cfg_m3_flow?>" size="8" maxlength="6">kbits/sec</td>
                                <td width="25%" id="td_left"><?=$m_m4?></td>
                                <td><input type="text" name="m4_flow" id="m4_flow" value="<?=$cfg_m4_flow?>" size="8" maxlength="6">kbits/sec</td>
                            </tr>
							<tr>
                                <td width="25%" id="td_left"><?=$m_m5?></td>
                                <td><input type="text" name="m5_flow" id="m5_flow" value="<?=$cfg_m5_flow?>" size="8" maxlength="6">kbits/sec</td>
                                <td width="25%" id="td_left"><?=$m_m6?></td>
                                <td><input type="text" name="m6_flow" id="m6_flow" value="<?=$cfg_m6_flow?>" size="8" maxlength="6">kbits/sec</td>
                            </tr>
							<tr>
                                <td width="25%" id="td_left"><?=$m_m7?></td>
                                <td><input type="text" name="m7_flow" id="m7_flow" value="<?=$cfg_m7_flow?>" size="8" maxlength="6">kbits/sec</td>
                            </tr>
						</table>
					</td>
				</tr>
				</tbody>	
				<tbody id="bysta_part" style="display:none;">
				<tr>
                    <td width="25%" id="td_left">
                        <?=$m_qos_enable?>
                    </td>
                    <td id="td_right">&nbsp;&nbsp;
                        <?=$G_TAG_SCRIPT_START?>genSelect("qos_enable", [0,1], ["<?=$m_disable?>","<?=$m_enable?>"], "on_change_enable(this)");<?=$G_TAG_SCRIPT_END?>
                    </td>
                </tr>
				<tr>
					<td colspan="2">
						<table class="table_tool" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
							<tr>
								<td class="table_tool_td" valign="middle" colspan="2"><b><?=$m_pri_title?></b></td>
							</tr>	
							<tr>
								<td id="td_left" width="25%">
									<?=$m_http?>
								</td>
								<td id="td_right">	
									<?=$G_TAG_SCRIPT_START?>genCheckBox("http","");<?=$G_TAG_SCRIPT_END?>	
								</td>
							</tr>	
							<tr>
								<td id="td_left">
									<?=$m_auto?>
								</td>
								<td id="td_right">	
									<?=$G_TAG_SCRIPT_START?>genCheckBox("auto","");<?=$G_TAG_SCRIPT_END?>	
									<?=$m_auto_msg?>
								</td>
							</tr>																							
						</table>
					</td>
				</tr>	
				<tr>
					<td colspan="2">
						<table class="table_tool" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
							<tr>
								<td class="table_tool_td" valign="middle" colspan="2"><b><span id="add_edit_title"><?=$m_add_title?></span></b></td>
							</tr>						
							<tr>
								<td id="td_left" width="25%">
									<?=$m_name?>
								</td>
								<td id="td_right">&nbsp;
									<input class="text" maxlength="32" name="name" size="32" value="">
								</td>
							</tr>	
							<tr>
								<td id="td_left">
									<?=$m_priority?>
								</td>
								<td id="td_right">&nbsp;
									<?=$G_TAG_SCRIPT_START?>genSelect("priority", [3,2,1,0], ["<?=$m_bk?>","<?=$m_be?>","<?=$m_vi?>","<?=$m_vo?>"], "");<?=$G_TAG_SCRIPT_END?>
								</td>
							</tr>	
							<tr>
								<td id="td_left"><?=$m_protocol?></td>
								<td id="td_right">&nbsp;
										<?=$G_TAG_SCRIPT_START?>genSelect("protocol_select", [0,1,2,3,4,5], ["<?=$m_any?>","<?=$m_tcp?>","<?=$m_udp?>","<?=$m_both?>","<?=$m_icmp?>","<?=$m_other?>"], "on_change_pro()");<?=$G_TAG_SCRIPT_END?>
									<input class="text" maxlength="3" name="protocol" size="8" maxlength="5" value="">							
								</td>
							</tr>
							<tr>
								<td id="td_left">
									<?=$m_host_1_ip?>
								</td>
								<td id="td_right">&nbsp;
									<input class="text" maxlength="15" name="host_1_ip_s" size="15" value="">&nbsp;-
									<input class="text" maxlength="15" name="host_1_ip_e" size="15" value="">
								</td>
							</tr>	
							<tr>
								<td id="td_left">
									<?=$m_host_1_port?>
								</td>
								<td id="td_right">&nbsp;
									<input class="text" maxlength="5" name="host_1_port_s" size="15" value="">&nbsp;-
									<input class="text" maxlength="5" name="host_1_port_e" size="15" value="">
								</td>
							</tr>	
							<tr>
								<td id="td_left">
									<?=$m_host_2_ip?>
								</td>
								<td id="td_right">&nbsp;
									<input class="text" maxlength="15" name="host_2_ip_s" size="15" value="">&nbsp;-
									<input class="text" maxlength="15" name="host_2_ip_e" size="15" value="">
								</td>
							</tr>	
							<tr>
								<td id="td_left">
									<?=$m_host_2_port?>
								</td>
								<td id="td_right">&nbsp;
									<input class="text" maxlength="5" name="host_2_port_s" size="15" value="">&nbsp;-
									<input class="text" maxlength="5" name="host_2_port_e" size="15" value="">
								</td>
							</tr>	
							<tr>
								<td id="td_left">
								</td>
								<td id="td_right">&nbsp;
									<input type="button" id="b_add" name="b_add" value=" <?=$m_b_add?> " onclick="do_add()">&nbsp;&nbsp;&nbsp;
									<input type="button" id="b_cancel" name="b_cancel" value=" <?=$m_b_cancel?> " onclick="do_cancel()">	
								</td>
							</tr>																																																										
						</table>
					</td>
				</tr>	
				<tr>
					<td colspan="2">
						<table class="table_tool" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
							<tr>
								<td class="table_tool_td" valign="middle" colspan="2"><b><?=$m_rule_title?></b></td>
							</tr>	
							<tr>
								<td colspan="2">
									<table width="100%" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?> style="padding-left:3px;">
										<tr class="list_head" align="left">
											<td width="20">
												&nbsp;
											</td>	
											<td width="60">
												<?=$m_name?>
											</td>
											<td width="70" align="left">
												<?=$m_priority?>
											</td>
											<td width="110">
												<?=$m_host_1_ip?>
											</td>											
											<td width="110">
												<?=$m_host_2_ip?>
											</td>	
											<td width="70">
												<?=$protocol_port?>
											</td>	
											<td width="30">
												<?=$m_edit?>
											</td>																							
											<td>
												<?=$m_del?>
											</td>																																																																					
										</tr>	
									</table>
									<div class="div_tab">
										<table id="acl_tab" width="100%" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?> style="padding-left:3px;">						
<script>
var qos_index=1;
var any="<?=$m_any?>";
var strqos=""; 
var rule_prrule_protocoliority="";

while(qos_index <=all_index)
{
	strqos="";
	if(qos_index%2==1)
	{
		strqos+="<tr style='background:#CCCCCC;'>";
	}	
	else
	{
		strqos+="<tr style='background:#B3B3B3;'>";
	}
	strqos+="<td width=\"20\"><input name=\"rule_state"+qos_index+"\" id=\"rule_state"+qos_index+"\" type=\"checkbox\" value=\"1\" ";
	if(qos_list[qos_index][2]=="1")
	{	
		strqos+="checked";
	}
	strqos+="></td>\n<td width=\"60\" align=\"left\">"+Tablename(qos_list[qos_index][1])+"</td>\n<td width=\"80\" align=\"left\">"+qos_list[qos_index][3]+"</td>";
	if(qos_list[qos_index][6]=="")
	{
		strqos+="<td width=\"100\">"+any+"</td>";
	}
	else
	{
		strqos+="<td width=\"100\">"+qos_list[qos_index][6]+"&nbsp;~&nbsp<br>"+qos_list[qos_index][7]+"</td>";
	}
	if(qos_list[qos_index][8]=="")
	{
		strqos+="<td width=\"100\">"+any+"</td>";
	}
	else
	{
		strqos+="<td width=\"100\">"+qos_list[qos_index][8]+"&nbsp;~&nbsp<br>"+qos_list[qos_index][9]+"</td>";
	}
	if(qos_list[qos_index][5] == "0"){rule_prrule_protocoliority ="<?=$m_any?>";}
	else if(qos_list[qos_index][5] == "1"){rule_prrule_protocoliority ="<?=$m_tcp?>";}
	else if(qos_list[qos_index][5] == "2"){rule_prrule_protocoliority ="<?=$m_udp?>";}
	else if(qos_list[qos_index][5] == "3"){rule_prrule_protocoliority ="<?=$m_both?>";}
	else if(qos_list[qos_index][5] == "4"){rule_prrule_protocoliority ="<?=$m_icmp?>";}
	else {rule_prrule_protocoliority ="<?=$m_other?>";}
	strqos+="<td width=\"70\">"+rule_prrule_protocoliority+"<br>";	
	if(qos_list[qos_index][10]=="")
	{
		strqos+=any+"<br>";
	}
	else
	{
		strqos+=qos_list[qos_index][10]+"/"+qos_list[qos_index][11]+"<br>";
	}
	if(qos_list[qos_index][12]=="")
	{
		strqos+=any+"</td>";
	}
	else
	{
		strqos+=qos_list[qos_index][12]+"/"+qos_list[qos_index][13]+"</td>";
	}
	strqos+="<td width=\"30\">"+print_rule_edit(qos_index)+"</td>";
	strqos+="<td id=\"adjust_td\">"+print_rule_del(qos_index)+"</td>";
	strqos+="</tr>";
	document.write(strqos);
	qos_index++;
}
</script>																																			
										</table>	
									</div>											
								</td>
							</tr>	
						</table>
					</td>
				</tr>																		
				</tbody>
				<tr>
					<td colspan="2">
<?=$G_APPLY_BUTTON?>
					</td>
				</tr>
			</table>
<!-- ________________________________  Main Content End _______________________________ -->
		</td>
	</tr>
</table>
</form>
<form name="final_form" id="final_form" method="post" action="<?=$MY_NAME?>.php"  onsubmit="">
<input type="hidden" name="ACTION_POST" value="<?=$MY_ACTION?>">
<input type="hidden" name="f_qos_type"  value="">
<input type="hidden" name="f_assoc_enable"  value="">
<input type="hidden" name="f_flow_enable"       value="">
<input type="hidden" name="pri_assoc"  value="">
<input type="hidden" name="m1_assoc"  value="">
<input type="hidden" name="m2_assoc"  value="">
<input type="hidden" name="m3_assoc"  value="">
<input type="hidden" name="m4_assoc"  value="">
<input type="hidden" name="m5_assoc"  value="">
<input type="hidden" name="m6_assoc"  value="">
<input type="hidden" name="m7_assoc"  value="">
<input type="hidden" name="pri_flow" value="">
<input type="hidden" name="m1_flow" value="">
<input type="hidden" name="m2_flow" value="">
<input type="hidden" name="m3_flow" value="">
<input type="hidden" name="m4_flow" value="">
<input type="hidden" name="m5_flow" value="">
<input type="hidden" name="m6_flow" value="">
<input type="hidden" name="m7_flow" value="">

<input type="hidden" name="f_rule_del"	value="">
<input type="hidden" name="rule_edit"		value="">
<input type="hidden" name="f_host_1_ip_r"		value="">
<input type="hidden" name="f_host_2_ip_r"		value="">
<input type="hidden" name="f_host_1_port_r"		value="">
<input type="hidden" name="f_host_2_port_r"		value="">
<input type="hidden" name="f_add"				value="">
<input type="hidden" name="f_qos_enable"		value="">
<input type="hidden" name="http"				value="">
<input type="hidden" name="auto"				value="">
<input type="hidden" name="name"				value="">
<input type="hidden" name="priority"			value="">
<input type="hidden" name="protocol_select"		value="">
<input type="hidden" name="protocol"			value="">
<input type="hidden" name="host_1_ip_s"			value="">
<input type="hidden" name="host_1_ip_e"			value="">
<input type="hidden" name="host_1_port_s"		value="">
<input type="hidden" name="host_1_port_e"		value="">
<input type="hidden" name="host_2_ip_s"			value="">
<input type="hidden" name="host_2_ip_e"			value="">
<input type="hidden" name="host_2_port_s"		value="">
<input type="hidden" name="host_2_port_e"		value="">
<script>
var qos_index=1;
var str; 
while(qos_index <=all_index)
{
	str="<input type=\"hidden\" name=\"rule_state"+qos_index+"\" id=\"f_rule_state"+qos_index+"\" value=\"\">";
	document.write(str);
	qos_index++;
}
</script>
</form>
</body>
</html>
				

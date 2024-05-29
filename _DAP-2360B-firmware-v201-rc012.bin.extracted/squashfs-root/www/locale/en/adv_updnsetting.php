<?
$m_context_title = "Uplink and Downlink Setting";
$m_uplink = "Uplink";
$m_downlink = "Downlink";
$m_uplink_interface = "Uplink Interface";
$m_downlink_interface = "Downlink Interface";
$m_uplink_bandwidth = "Uplink Bandwidth";
$m_downlink_bandwidth = "Downlink Bandwidth";
if($TITLE=="DAP-2590")
{
	$m_range = "(1~150)";
}
else
{
	$m_range = "(1~300)";
}
$m_band_2.4G = "2.4GHz";
$m_band_5G = "5GHz";
$m_ethernet = "Ethernet";
$m_lan1 = "LAN1";
$m_lan2 = "LAN2";
$m_ethernet2 = "Ethernet2";
$m_primaryssid = "Primary-ssid";
$m_multissid1 = "Multi-ssid1";
$m_multissid2 = "Multi-ssid2";
$m_multissid3 = "Multi-ssid3";
$m_multissid4 = "Multi-ssid4";
$m_multissid5 = "Multi-ssid5";
$m_multissid6 = "Multi-ssid6";
$m_multissid7 = "Multi-ssid7";
$m_wds1 = "WDS1";
$m_wds2 = "WDS2";
$m_wds3 = "WDS3";
$m_wds4 = "WDS4";
$m_wds5 = "WDS5";
$m_wds6 = "WDS6";
$m_wds7 = "WDS7";
$m_wds8 = "WDS8";
$a_empty_value_for_speed	="Please input value for the rate!";
$a_invalid_value_for_speed	="Invalid value for the rate !";
if($TITLE=="DAP-2590")
{
	$a_invalid_range_for_speed  ="Downlink or Uplink bandwidth range should be 1~150 mega bits/sec!";
}
else
{
	$a_invalid_range_for_speed  ="Downlink or Uplink bandwidth range should be 1~300 mega bits/sec!";
}
$a_w2e_larger_than_max      ="Uplink bandwidth value must larger than max uplink value in traffic manager!";
$a_e2w_larger_than_max      ="Downlink bandwidth value must larger than max downlink value in traffic manager!";
?>

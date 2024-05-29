<?
$m_context_title = "Performance Settings";
$m_band = "Wireless band";
$m_band_2.4G = "2.4GHz";
$m_band_5G = "5GHz";
$m_wl_enable = "Wireless";
$m_disable = "Disable";
$m_enable  = "Enable";
$m_off = "Off";
$m_on  = "On";
$m_wlmode = "Wireless Mode";
$m_wlmode_n_g_b = "Mixed 802.11n, 802.11g and 802.11b";
$m_wlmode_n_g = "Mixed 802.11n and 802.11g";
$m_wlmode_g_b = "Mixed 802.11g and 802.11b";
$m_wlmode_n = "802.11n Only";
$m_wlmode_n_a = "Mixed 802.11n, 802.11a";
$m_wlmode_a = "802.11a Only";
$m_wlmode_ac = "Mixed 802.11ac";
$m_rate = "Data Rate";
$m_best	= "Best(Up to 300)";
$m_best_ac	= "Best(Up to 1300)";
$m_best_54	= "Best(Up to 54)";
$m_54	= "54";
$m_48	= "48";
$m_36	= "36";
$m_24	= "24";
$m_18	= "18";
$m_12	= "12";
$m_9	= "9";
$m_6	= "6";
$m_11	= "11";
$m_5.5	= "5.5";
$m_2	= "2";
$m_1	= "1";
$m_beacon_interval	="Beacon Interval (40-500)";
$m_rts			="RTS Threshold (256-2346)";
$m_frag			="Fragmentation (256-2346)";
$m_dtim			="DTIM Interval (1-15)";
$m_power = "Transmit Power";
$m_ms = "(&micro;s)";
$m_bandrate_state = "Multicast Bandwidth Control";
$m_bandrate = "Maximum Multicast Bandwidth";

$m_sw_power = "SW Transmit Power";
$m_ampdu = "AMPDU";
$m_amsdu = "AMSDU";
$m_chainmask = "ChainMask";
$m_1x1 = "1x1";
$m_2x2 = "2x2";
$m_chain0 = "chain0";
$m_chain1 = "chain1";
$m_chain0and1 = "chain0 and chain1";
$m_wmm = "WMM (Wi-Fi Multimedia)";
$m_shortgi = "Short GI";
$m_limit_state = "Connection Limit";
$m_limit_num = "User Limit (0 - 64)";
$m_utilization = "Network Utilization";
$m_0 = "0";
$m_10 = "10";
$m_20 = "20";
$m_30 = "30";
$m_40 = "40";
$m_50 = "50";
$m_60 = "60";
$m_70 = "70";
$m_80 = "80";
$m_90 = "90";
$m_100 = "100";
$m_180 = "180";
$m_75 = "75";
$m_25 = "25";
$m_12.5 = "12.5";
$m_igmp = "IGMP Snooping";
$m_link_integrality="Link Integrity";
$m_ack_timeout="Ack Time Out";
$m_mbps = "(Mbps)";
$m_multicast_rate  = "WLAN Multicast TX Rate ";
$m_mcast_a = "Multicast Rate for 5G Band";
$m_mcast_g = "Multicast Rate for 2.4G Band";
$m_ht2040 = "HT20/40 Coexistence";
$m_aging = "Aging out";
$m_rssi ="RSSI";
$m_date_rate="Date Rate ";
$m_aging_date_rate="Date Rate Threshod";
$m_aging_rssi="RSSI Threshod";
$m_acl_rssi="ACL RSSI";
$m_acl_rssi_thre="ACL RSSI Threshod";
$m_11n_preferred="11n Preferred";
$m_5g_preferred="Band Steering";
$m_5g_preferred_age="Band Steering Age";
$m_5g_preferred_diff="Band Steering Difference";
$m_5g_preferred_refuse="Band Steering Refuse Number";
if(query("/runtime/web/display/ack_timeout_range")=="0")
{
$m_ack_timeout_g_msg = " (2.4GHz, 48~200)";
$m_ack_timeout_a_msg = " (5GHz, 25~200)";
	$a_invalid_ack_timeoutg ="The Ack TimeOut value range is 48 ~ 200.";
	$a_invalid_ack_timeouta ="The Ack TimeOut value range is 25 ~ 200.";
}
else
{
	$m_ack_timeout_g_msg = " (2.4GHz, 64~200)";
	$m_ack_timeout_a_msg = " (5GHz, 50~200)";
	$a_invalid_ack_timeoutg ="The Ack TimeOut value range is 64 ~ 200.";
	$a_invalid_ack_timeouta ="The Ack TimeOut value range is 50 ~ 200.";
}
$m_multicast_rate  = "Multicast Rate ";
$m_m2u = "Transfer DHCP Offer to Unicast";

$a_invalid_txswpower = "The SW Transmit Power value range is 0 ~ 30.";
$a_invalid_txswpower_wapn08a = "The SW Transmit Power value range is 1 ~ 30.";
$a_invalid_bi           ="The Beacon Interval value range is 40 ~ 500.";
$a_invalid_rts		="The RTS Threshold value range is 256 ~ 2346.";
$a_invalid_frag		="The Fragmentation value range is 256 ~ 2346.";
$a_invalid_dtim		="The DTIM Interval value range is 1~15.";
$a_invalid_limit_num	="The range of 'User Limit' is from 0 to 64.";
$a_invalid_bandrate		="The range of 'Bandwidth Rate' is from 1 to 1024.";
$a_invalid_preferred_5g_rssi = "The Band Steering rssi value is digit.";
$a_preferred_5g_rssi_range = "The Band Steering rssi value should be 0~100.";
$a_invalid_preferred_5g_age = "The Band Steering age value is digit.";
$a_preferred_5g_age_range = "The Band Steering age value should be 0~600.";
$a_invalid_preferred_5g_diff = "The Band Steering difference value is digit.";
$a_preferred_5g_diff_range = "The Band Steering difference value should be 0~32.";
$a_invalid_preferred_5g_refuse = "The Band Steering refuse number value is digit.";
$a_preferred_5g_refuse_range = "The Band Steering refuse number value should be 0~10.";
$a_il_off_wl	= "Not Support 5GHz.";
$a_two_band_share_the_same_bw	= "Multicast Bandwidth of the other band will get the same settings like this band.";
?>

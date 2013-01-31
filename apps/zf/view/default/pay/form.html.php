<preload type="js" list="jform.js" path="/public/js/" />
<include file="../header" />
<cssstyle href="pay.css" />

<div id="content">
	<ul id="pay_mode_list">
    <loop $payModeList $v>
    	<li>{$v['name']}</li>
    </loop>
    </ul>
	<div id="payform">
		<form id="form1" name="form1" method="post" action="/index.php?">
			<input type="hidden" name="hiddenField" id="hiddenField" />
			<if $payModeInfo['chanel_sign']=='yeepay'>
			<ul class="op_banks" id="yeepaybanks">
				<li title="中国工商银行" class="gs">
					<input type="radio" name="pd_FrpId_radio" id="2" value="ICBC-NET-B2C" onclick="choose_bank('ICBC-NET-B2C')">
					<label for="2" class="bankicon"></label>
				</li>
				<li title="招商银行" class="zs">
					<input type="radio" name="pd_FrpId_radio" id="3" value="CMBCHINA-NET-B2C" onclick="choose_bank('CMBCHINA-NET-B2C')">
					<label for="3" class="bankicon"></label>
				</li>
				<li title="中国建设银行" class="js">
					<input type="radio" name="pd_FrpId_radio" id="4" value="CCB-NET-B2C" onclick="choose_bank('CCB-NET-B2C')">
					<label for="4" class="bankicon"></label>
				</li>
				<li title="中国农业银行" class="ny">
					<input type="radio" name="pd_FrpId_radio" id="5" value="ABC-NET-B2C" onclick="choose_bank('ABC-NET-B2C')">
					<label for="5" class="bankicon"></label>
				</li>
				<li title="中国银行" class="zg">
					<input type="radio" name="pd_FrpId_radio" id="6" value="BOC-NET-B2C" onclick="choose_bank('BOC-NET-B2C')">
					<label for="6" class="bankicon"></label>
				</li>
				<li title="上海浦东发展银行" class="pf">
					<input type="radio" name="pd_FrpId_radio" id="7" value="SPDB-NET-B2C" onclick="choose_bank('SPDB-NET-B2C')">
					<label for="7" class="bankicon"></label>
				</li>
				<li title="广发银行" class="gf">
					<input type="radio" name="pd_FrpId_radio" id="8" value="GDB-NET-B2C" onclick="choose_bank('GDB-NET-B2C')">
					<label for="8" class="bankicon"></label>
				</li>
				<li title="中国光大银行" class="gd">
					<input type="radio" name="pd_FrpId_radio" id="9" value="CEB-NET-B2C" onclick="choose_bank('CEB-NET-B2C')">
					<label for="9" class="bankicon"></label>
				</li>
				<li title="中国民生银行" class="ms">
					<input type="radio" name="pd_FrpId_radio" id="10" value="CMBC-NET-B2C" onclick="choose_bank('CMBC-NET-B2C')">
					<label for="10" class="bankicon"></label>
				</li>
				<li title="中信银行" class="zx">
					<input type="radio" name="pd_FrpId_radio" id="11" value="ECITIC-NET-B2C" onclick="choose_bank('ECITIC-NET-B2C')">
					<label for="11" class="bankicon"></label>
				</li>
				<li title="兴业银行" class="xy">
					<input type="radio" name="pd_FrpId_radio" id="12" value="CIB-NET-B2C" onclick="choose_bank('CIB-NET-B2C')">
					<label for="12" class="bankicon"></label>
				</li>
				<li title="平安银行" class="pa">
					<input type="radio" name="pd_FrpId_radio" id="13" value="PINGANBANK-NET" onclick="choose_bank('PINGANBANK-NET')">
					<label for="13" class="bankicon"></label>
				</li>
				<li title="深圳发展银行" class="sz">
					<input type="radio" name="pd_FrpId_radio" id="14" value="SDB-NET-B2C" onclick="choose_bank('SDB-NET-B2C')">
					<label for="14" class="bankicon"></label>
				</li>
				<li title="交通银行" class="jt">
					<input type="radio" name="pd_FrpId_radio" id="15" value="BOCO-NET-B2C" onclick="choose_bank('BOCO-NET-B2C')">
					<label for="15" class="bankicon"></label>
				</li>
				<li title="北京银行" class="bj">
					<input type="radio" name="pd_FrpId_radio" id="16" value="BCCB-NET-B2C" onclick="choose_bank('BCCB-NET-B2C')">
					<label for="16" class="bankicon"></label>
				</li>
				<li title="南京银行" class="nj">
					<input type="radio" name="pd_FrpId_radio" id="17" value="NJCB-NET-B2C" onclick="choose_bank('NJCB-NET-B2C')">
					<label for="17" class="bankicon"></label>
				</li>
				<li title="宁波银行" class="nb">
					<input type="radio" name="pd_FrpId_radio" id="18" value="NBCB-NET-B2C" onclick="choose_bank('NBCB-NET-B2C')">
					<label for="18" class="bankicon"></label>
				</li>
				<li title="北京农村商业银行" class="bj_ns">
					<input type="radio" name="pd_FrpId_radio" id="19" value="BJRCB-NET-B2C" onclick="choose_bank('BJRCB-NET-B2C')">
					<label for="19" class="bankicon"></label>
				</li>
				<li title="渤海银行" class="bh">
					<input type="radio" name="pd_FrpId_radio" id="20" value="CBHB-NET-B2C" onclick="choose_bank('CBHB-NET-B2C')">
					<label for="20" class="bankicon"></label>
				</li>
				<li title="东亚银行" class="dy">
					<input type="radio" name="pd_FrpId_radio" id="21" value="HKBEA-NET-B2C" onclick="choose_bank('HKBEA-NET-B2C')">
					<label for="21" class="bankicon"></label>
				</li>
				<li title="上海银行" class="shanghai">
					<input type="radio" name="pd_FrpId_radio" id="22" value="SHB-NET-B2C" onclick="choose_bank('SHB-NET-B2C')">
					<label for="22" class="bankicon"></label>
				</li>
				<li title="中国邮政" class="youzheng">
					<input type="radio" name="pd_FrpId_radio" id="23" value="POST-NET-B2C" onclick="choose_bank('POST-NET-B2C')">
					<label for="23" class="bankicon"></label>
				</li>
				<li title="华夏银行" class="huaxia">
					<input type="radio" name="pd_FrpId_radio" id="24" value="HXB-NET-B2C" onclick="choose_bank('HXB-NET-B2C')">
					<label for="24" class="bankicon"></label>
				</li>
				<li title="浙商银行" class="zheshang">
					<input type="radio" name="pd_FrpId_radio" id="25" value="CZ-NET-B2C" onclick="choose_bank('CZ-NET-B2C')">
					<label for="25" class="bankicon"></label>
				</li>
				<li title="杭州银行" class="hangzhou">
					<input type="radio" name="pd_FrpId_radio" id="26" value="HZBANK-NET" onclick="choose_bank('HZBANK-NET')">
					<label for="26" class="bankicon"></label>
				</li>
            </ul>
			<else />
			
			</if>
		</form>
	</div>
</div>

<include file="../footer" />
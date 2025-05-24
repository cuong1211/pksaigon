<style>html,body { padding: 0; margin:0; }</style>
<div style="font-family:Arial,Helvetica,sans-serif; line-height: 1.5; font-weight: normal; font-size: 15px; color: #2F3044; min-height: 100%; margin:0; padding:0; width:100%; background-color:#edf2f7">
	<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;margin:0 auto; padding:0; max-width:600px">
		<tbody>

			<tr>
				<td align="left" valign="center">
					<div style="text-align:left; margin: 0 20px; padding: 40px; background-color:#ffffff; border-radius: 6px">
						<!--begin:Email content-->
						<div style="padding-bottom: 30px; font-size: 17px;">
							<strong>Chào mừng tới CheckCar.vn!</strong>
						</div>
						<div style="padding-bottom: 30px">Để kích hoạt tài khoản của bạn, vui lòng nhấp vào nút bên dưới để xác minh địa chỉ email của bạn.</div>
						<div style="padding-bottom: 40px; text-align:center;">
							<a href="{{ $confirmationUrl }}" rel="noopener" style="text-decoration:none;display:inline-block;text-align:center;padding:0.75575rem 1.3rem;font-size:0.925rem;line-height:1.5;border-radius:0.35rem;color:#ffffff;background-color:#009EF7;border:0px;margin-right:0.75rem!important;font-weight:600!important;outline:none!important;vertical-align:middle" target="_blank">Kích hoạt tài khoản</a>
						</div>
						<div style="padding-bottom: 30px">Mã kích hoạt: {{$confirmationCode}}</div>
						<div style="padding-bottom: 30px">Liên kết xác nhận này sẽ có hiệu lực trong vòng 60 phút</div>
						<div style="border-bottom: 1px solid #eeeeee; margin: 15px 0"></div>

						<!--end:Email content-->

						<br>CheckCar.vn Team.

					</div>
				</td>
			</tr>
		</tbody>
	</table>
</div>
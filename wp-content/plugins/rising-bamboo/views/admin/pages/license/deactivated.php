<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore
 */

use RisingBambooCore\App\App;
use RisingBambooCore\Core\View;
use RisingBambooCore\Helper\License as LicenseHelper;

?>
<div class="container mx-auto">
	<?php
	View::instance()->load('admin/pages/parts/header');
	?>
	<div id="rbb-core-license">
		<div class="pt-[14px] pb-[22px] text-[13px] leading-6">
			<h2 class="text-lg font-bold pb-[7px]">
				<?php
                    //phpcs:ignore
                    printf(esc_html__('Active %s theme with Purchase Code', App::get_domain()), esc_html($current_theme->Name));
				?>
			</h2>
			<p class="pb-[26px] text-[#3c434a]"><?php echo esc_html__('A valid license key is required for access to automatic theme update and support.', App::get_domain()); ?></p>
			<div class="bg-white rounded shadow-[5px_6px_10px_0_rgba(0,0,0,0.1)]">
				<div class="grid gap-4 grid-cols-2 border-b-[1px] border-[#ececed] pl-[30px] pr-4 py-2.5">
					<div class="col-span-1">
						<div class="font-medium flex items-center text-sm leading-[40px]">
							<i class="rbb-icon-brand-visa-1 text-[25px]"></i><label for="license_key" class="ml-2 inline-block"><?php echo esc_html__('License ID', App::get_domain()); ?></label>
						</div>
					</div>
					<div class="col-span-1 text-right">
						<span class="activated bg-[#00803a] h-10 leading-10 rounded-[40px] pl-3 pr-9 inline-flex items-center text-white">
							<i class="rbb-icon-check-4 text-[18px] relative"></i>
							<span class="ml-4 font-bold"><?php echo 'trial' !== $key['type'] ? esc_html__('Activated', App::get_domain()) : esc_html__('Activated For Trial', App::get_domain()); ?></span>
						</span>
					</div>
				</div>
				<div class="grid gap-4 gap-y-2 text-sm grid-cols-1 lg:grid-cols-2">
					<div class="col-span-1 text-gray-600 border-r px-6 py-6">
						<form class="rbb-license-form" data-action="rbb_deactivate_theme_license" method="POST">
							<div class="grid gap-4 gap-y-2 text-sm grid-cols-1">
								<div class="md:col-span-5">
									<div class="copy-license relative">
										<input type="text" name="license_key" id="license_key" readonly
												class="h-[50px] text-[0.8125rem] leading-[50px] border !border-[#e5e5e5] rounded-[3px] !px-4 w-full !bg-[#e5e5e5] focus:!shadow-none focus:!ring-[#e5e5e5] placeholder:text-[13px] !text-[#545454]"
												value="<?php echo esc_attr($key['token']); ?>"
										/>
										<input name="type" value="<?php echo 'trial' === $key['type'] ? 'trial' : 'paid'; ?>" type="hidden" />
										<div data-clipboard-target="#license_key" id="copy-btn" aria-describedby="tooltip" class="copy-btn bg-[#bdbdbd] hover:bg-[#666666] duration-300 cursor-pointer absolute right-0 top-0 h-[50px] w-[50px] rounded text-center flex justify-center items-center">
											<div class="rbb-tooltip" role="tooltip">
												<div class="rbb-tooltip-content text-[11px] font-normal"><?php echo esc_html__('Copy', App::get_domain()); ?></div>
												<div class="rbb-arrow" data-popper-arrow></div>
											</div>
											<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#fff" class="w-full" viewBox="0 0 16 16">
												<path fill-rule="evenodd" d="M4 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 5a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1h1v1a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h1v1z"/>
											</svg>
										</div >
									</div>
									<p class="text-[#3c434a] pt-3 pb-1.5">
										<?php echo 'trial' === $key['type'] ? esc_html__('The license key has been activated for trial.', App::get_domain()) : esc_html__('In order to register your purchase code on another domain, deregister it first by clicking the button below.', App::get_domain()); ?>
										<?php
										/* translators: %s: Expired date */
										echo ! empty($key['expired']) ? sprintf(esc_html__('It will expire on %s. ', App::get_domain()), esc_html(LicenseHelper::create_date_with_timezone($key['expired'])->format('r'))) : '';
										?>
									</p>
								</div>
								<div class="grid-cols-1">
									<div class="inline-flex items-end">
										<?php $button_text = 'trial' === $key['type'] ? esc_html__('Remove Trial License', App::get_domain()) : esc_html__('Deactivate Now', App::get_domain()); ?>
										<button data-confirm="true" data-confirm-title="<?php echo esc_html__('Are you sure to deactivate the license?', App::get_domain()); ?>" data-confirm-text="<?php echo esc_html__('You won\'t be able to revert this!', App::get_domain()); ?>" data-confirm-button="<?php echo esc_html__('Yes, Deactivate now', App::get_domain()); ?>" data-text="<?php echo esc_attr($button_text); ?>" type="submit" class="h-[50px] px-5 bg-[#c22500] hover:bg-[#01693a] duration-300 disabled:bg-gray-300 text-white font-bold rounded">
											<svg aria-hidden="true" role="status" class="hidden inline w-4 h-4 me-3 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
												<path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
											</svg>
											<span class="text-xs"><?php echo esc_html($button_text); ?></span>
										</button>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="col-span-1 text-gray-600 px-6 py-6">
						<p class="italic text-[#3c434a] leading-6"><?php echo esc_html__('Reminder! Purchase code used to activate your theme. Each purchase code can only be used for a single project or domain. If registered elsewhere or buy a new domain please deactivate that registration first.', App::get_domain()); ?></p>
						<div class="group-policy mt-11 mb-9">
							<div class="flex mb-[30px] -ml-2">
								<img class="w-[50px] h-[50px]" alt="support" src="<?php echo esc_url(RBB_CORE_DIST_URL . 'images/update.gif'); ?>" />
								<div class="pl-5">
									<h3 class="font-bold text-sm"><?php echo esc_html__('Live Updates', App::get_domain()); ?></h3>
									<p class="pt-2"><?php echo esc_html__('Fresh versions directly to your admin', App::get_domain()); ?></p>
								</div>
							</div>
							<div class="flex -ml-2">
								<img class="w-[50px] h-[50px]" alt="support" src="<?php echo esc_url(RBB_CORE_DIST_URL . 'images/support.gif'); ?>" />
								<div class="pl-5">
									<h3 class="font-bold text-sm"><?php echo esc_html__('Ticket Support', App::get_domain()); ?></h3>
									<p class="pt-2"><?php echo esc_html__('Direct help from our qualified support team', App::get_domain()); ?></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
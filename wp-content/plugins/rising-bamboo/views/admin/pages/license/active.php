<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore
 */

use RisingBambooCore\App\App;
use RisingBambooCore\Core\View;

?>
<div class="container mx-auto">
	<?php
	View::instance()->load('admin/pages/parts/header');
	?>
	<div id="rbb-core-license">
		<div class="pt-[14px] pb-[22px] text-[13px] leading-6">
			<h2 class="text-lg font-bold pb-[7px]">
				<?php

                // phpcs:ignore
                printf(esc_html__('Active %s theme with Purchase Code', App::get_domain()), $current_theme->Name);
				?>
			</h2>
			<p class="pb-[26px] text-[#3c434a]"><?php echo esc_html__('A valid license key is required for access to automatic theme update and support.', App::get_domain()); ?></p>
			<div class="bg-white rounded shadow-[5px_6px_10px_0_rgba(0,0,0,0.1)]">
				<div class="grid gap-4 grid-cols-2 border-b-[1px] border-[#ececed] pl-[30px] pr-4 py-2.5">
					<div class="col-span-1">
						<div class="font-medium flex items-center text-sm leading-[40px]">
							<i class="rbb-icon-brand-visa-1 text-[25px]"></i><label for="purchase_code" class="ml-4 text-[#1d2327] font-bold inline-block"><?php echo esc_html__('Purchase Code', App::get_domain()); ?></label>
						</div>
					</div>
					<div class="col-span-1 text-right">
						<span class="bg-[#c22500] rounded-[40px] md:pl-5 md:pr-6 px-3 py-2 inline-block text-white">
							<i class="rbb-icon-notification-filled-6 inline-block animate-[ring_4s_ease-in-out_.7s_infinite] origin-[50%_4px]"></i>
							<span class="ml-2 text-xs font-bold"><?php echo esc_html__('Not Activated', App::get_domain()); ?></span></span>
					</div>
				</div>
				<div class="grid gap-0 gap-y-2 text-sm grid-cols-1 lg:grid-cols-2">
					<div class="col-span-1 text-gray-600 border-r border-[#ececed] p-[30px]">
						<form class="rbb-license-form" data-action="rbb_active_theme_license" method="POST">
							<div class="grid gap-4 gap-y-2 text-sm grid-cols-1">
								<div class="md:col-span-5">
									<input type="text" name="purchase_code" id="purchase_code"
											class="h-[50px] leading-[50px] text-[0.8125rem] border !border-[#e2e2e2] rounded-[3px] !px-4 w-full !bg-[#f7f7f7] focus:!shadow-[0_0_0_1px_#01693a] text-[#545454] focus:!ring-[#00803a] placeholder-leading-[50px] placeholder:text-[13px]"
											value=""
											placeholder="<?php echo esc_attr(__('Enter your purchase code', App::get_domain())); ?>"
									/>
									<p class="text-[#3c434a] pt-3 pb-1.5">
										<?php
                                        //phpcs:ignore
                                        printf(__('You can learn how to find your purchase code <a class="text-[#3a7bbd] hover:text-[#00803a] font-bold capitalize" target="_blank" href="%s"> here </a', App::get_domain()), esc_attr($api_config['get_purchase_code']));
										?>
									</p>
								</div>
								<div class="grid-cols-1">
									<div class="inline-flex items-end">
										<button data-text="<?php echo esc_attr(__('Active Now', App::get_domain())); ?>" type="submit" class="duration-300 bg-[#00803a] hover:bg-[#005728] text-white font-bold h-[50px] px-5 rounded">
											<svg aria-hidden="true" role="status" class="hidden inline w-4 h-4 me-3 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
												<path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
											</svg>
											<span class="text-xs"><?php echo esc_html__('Active Now', App::get_domain()); ?></span>
										</button>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="col-span-1 px-[30px] pt-6 pb-[30px]">
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

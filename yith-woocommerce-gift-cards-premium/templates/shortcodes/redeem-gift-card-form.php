<?php


if ( current_user_can('administrator') || current_user_can('manage_woocommerce') || apply_filters( 'ywgc_redeem_shortcode_access_condition', false ) ){
    ?>

    <form method="post" class="form-redeem-gift-card" name="form-redeem-gift-card">
            <label for="ywgc-check-code" style="margin-right: 15px;"><?php _e ( "Gift Card code: ", 'yith-woocommerce-gift-cards' ); ?></label>
            <input type="text" name="ywgc-check-code" id="ywgc-check-code" value="" placeholder="<?php _e ( "ENTER CODE", 'yith-woocommerce-gift-cards' ); ?>" style="width: 300px; margin-right: 15px;">
            <label for="ywgc-used-amount" class="ywgc-used-amount-label" style="margin-right: 15px;"><?php _e ( "Used amount: ", 'yith-woocommerce-gift-cards' ); ?></label>
            <input type="text" name="ywgc-used-amount" id="ywgc-used-amount" value="" placeholder="<?php _e ( "ENTER AMOUNT", 'yith-woocommerce-gift-cards' ); ?>" style="width: 300px; margin-right: 10px;">
            <button type="submit"><?php _e ( "Submit", 'yith-woocommerce-gift-cards' ); ?></button>
    </form>

    <?php

	if ( isset( $_POST["ywgc-check-code"] ) && isset( $_POST["ywgc-used-amount"] ) ){

		$code =  $_POST["ywgc-check-code"];
		$used_amount =  $_POST["ywgc-used-amount"];

		$args = array(
			'gift_card_number' => $code,
		);

		$gift_card = new YITH_YWGC_Gift_Card( $args );

		if ( ! is_object( $gift_card ) || !$gift_card->exists() ){
			echo '<div style="color: red; margin-top: 10px;">' . esc_html__( "The code added is not associated to any existing gift card.", 'yith-woocommerce-gift-cards' ) . '</div>';
			echo '<br>';
		}
		else{
			if ( is_object( $gift_card ) && $gift_card->ID != 0 && $gift_card->has_sufficient_credit($used_amount) ){
				$new_balance = $gift_card->get_balance() - (float)$used_amount;
				$gift_card->update_balance($new_balance);
				echo '<div style="background-color: #edf8c0; padding: 15px; width: fit-content; margin-top: 10px;">' . esc_html__( "The gift card has been redeemed successfully.", 'yith-woocommerce-gift-cards' ) . '<br><br>' . esc_html__( "New gift card balance: ", 'yith-woocommerce-gift-cards' ) . '<span style="font-weight: bold">' .wc_price( $gift_card->get_balance() ) . '</span></div>';
				echo '<br>';
			}
			
			else{
				echo '<div style="color: red; margin-top: 10px;">' . esc_html__( "The gift card balance is not enough to cover this order amount.", 'yith-woocommerce-gift-cards' ) . '</div><br>';
				echo '<div style="color: red;">' . esc_html__( "Gift card balance: ", 'yith-woocommerce-gift-cards' ) . '<span style="font-weight: bold;">' . wc_price( $gift_card->get_balance() ) . '</span></div>';
				echo '<br>';
			}
		}
	}
}


<?php

namespace App\Policies;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CartPolicy
{
    /**
     * Menentukan apakah pengguna dapat mengubah item keranjang.
     * Aturannya: Aksi diizinkan jika ID pengguna yang login sama dengan user_id di item keranjang.
     */
    public function update(User $user, Cart $cart): bool
    {
        return $user->id === $cart->user_id;
    }

    /**
     * Menentukan apakah pengguna dapat menghapus item keranjang.
     * Aturannya sama dengan update.
     */
    public function delete(User $user, Cart $cart): bool
    {
        return $user->id === $cart->user_id;
    }
}

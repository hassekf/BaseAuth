<form wire:submit.prevent="submit" class="space-y-4">

    <input
        type="text"
        wire:model.lazy="name"
        autocomplete="name"
        placeholder="Nome"
    />

    <input
        type="email"
        wire:model.lazy="email"
        autocomplete="email"
        placeholder="Email"
    />

    <input
        type="password"
        wire:model.lazy="password"
        autocomplete="new-password"
        placeholder="Senha"
    />

    <input
        type="password"
        wire:model.lazy="password_confirmation"
        autocomplete="new-password"
        placeholder="Confirme a senha"
    />

    <button type="submit">
        Criar conta
    </button>

</form>

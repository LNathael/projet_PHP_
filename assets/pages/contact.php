<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars($_POST['nom']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Exemple d'envoi par email
    $to = "contact@projetachat.com";
    $subject = "Nouveau message de contact - $nom";
    $headers = "From: $email";

    if (mail($to, $subject, $message, $headers)) {
        $success = "Votre message a été envoyé avec succès !";
    } else {
        $error = "Une erreur s'est produite lors de l'envoi du message. Veuillez réessayer.";
    }
}
?>

<?php include '../includes/header.php'; ?>
<main>
    <h1>Contactez-nous</h1>

    <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST" action="">
        <label for="nom">Nom</label>
        <input type="text" id="nom" name="nom" placeholder="Votre nom" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Votre email" required>

        <label for="message">Message</label>
        <textarea id="message" name="message" placeholder="Votre message" rows="5" required></textarea>

        <button type="submit">Envoyer</button>
    </form>
</main>
<?php include '../includes/footer.php'; ?>

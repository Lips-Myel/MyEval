<?php
namespace App\Service;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use App\Entity\User;

class JWTManager
{
    private JWTTokenManagerInterface $jwtManager;
    private JWTEncoderInterface $jwtEncoder;

    public function __construct(JWTTokenManagerInterface $jwtManager, JWTEncoderInterface $jwtEncoder)
    {
        $this->jwtManager = $jwtManager;
        $this->jwtEncoder = $jwtEncoder;
    }

    /**
     * Crée un JWT pour un utilisateur avec des claims personnalisés.
     *
     * @param User $user
     * @return string
     */
    public function createJWT(User $user): string
    {
        // Récupérer les claims personnalisés
        $customClaims = $user->getJWTCustomClaims();

        // Créer un token temporaire pour extraire le payload
        $rawToken = $this->jwtManager->create($user);
        $decodedPayload = $this->jwtEncoder->decode($rawToken);

        if (!is_array($decodedPayload)) {
            throw new \RuntimeException("Erreur lors du décodage du token initial.");
        }

        // Fusionner le payload existant avec les claims personnalisés
        $tokenPayload = array_merge($decodedPayload, $customClaims);

        // Encoder le token avec les claims personnalisés
        return $this->jwtEncoder->encode($tokenPayload);
    }

    /**
     * Décoder et vérifier un JWT.
     *
     * @param string $token
     * @return array|null
     */
    public function parseJWT(string $token): ?array
    {
        try {
            return $this->jwtEncoder->decode($token) ?: null;
        } catch (\Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException $e) {
            error_log('Erreur lors du décodage du token : ' . $e->getMessage());
        }
        return null;
    }
}

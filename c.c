#include <stdio.h>

/* Prototypes : permettent aux sous-programmes fournis d'appeler ceux que vous allez ecrire */
int sommeDiviseurs(int n);
int estParfait(int n);

/* Retourne 1 si n est premier, 0 sinon */
int estPremier(int n)
{
    int d;
    if (n < 2)
        return 0;
    for (d = 2; d * d <= n; d++)
        if (n % d == 0)
            return 0;
    return 1;
}

/* ====== TODO A : ecrire ici la fonction sommeDiviseurs ====== */
/* Somme des diviseurs propres de n (diviseurs differents de n), n >= 1 */
int sommeDiviseurs(int n)
{
    int i, somme = 0;
    for (i = 1; i <= n / 2; i++)
        if (n % i == 0)
            somme += i;
    return somme;
}
/* ====== TODO B : ecrire ici la fonction estParfait ====== */
/* Retourne 1 si n est parfait, 0 sinon */
int estParfait(int n)
{
    if (sommeDiviseurs(n) == n){
        return 1;
    }else{
        return 0;
    } 
}
/* Si 2^p - 1 est premier, retourne 2^(p-1) * (2^p - 1), sinon 0 */
int parfaitEuclide(int p)
{
    int i, a = 1, m;
    for (i = 1; i < p; i++)
        a = a * 2;              /* a = 2^(p-1) */
    m = 2 * a - 1;              /* m = 2^p - 1 */
    if (estPremier(m))
        return a * m;
    return 0;
}

/* Procedure : affiche les nombres parfaits de 2 a max */
void afficherParfaits(int max)
{
    int i;
    for (i = 2; i <= max; i++)
        if (estParfait(i))
            printf("%d ", i);
    printf("\n");
}

int main()
{
    int p, r;

    /* TODO 1 : afficher estParfait(28), puis estParfait(12)
       (une ligne chacun, sous la forme "estParfait(28) = 1") */
    printf("estParfait(28) = %d\n", estParfait(28));
    printf("estParfait(12) = %d\n", estParfait(12));
    /* TODO 2 : afficher le texte "Parfaits jusqu'a 1000 : " puis appeler afficherParfaits(1000) */
    printf("Parfaits jusqu'a 1000 : ");
    afficherParfaits(1000);
    /* TODO 3 : pour p de 2 a 7 : calculer r = parfaitEuclide(p) et afficher "p = ... : r" ;
       si r n'est pas nul, afficher aussi, sur la meme ligne, " (estParfait : 0 ou 1)"
       (utiliser estParfait) */
       for (p = 2; p <= 7; p++) {
        r = parfaitEuclide(p);
        printf("p = %d : %d", p, r);
        if(r != 0) {
            printf("(estParfait 0 ou 1: %d)",estParfait(r));
        }
        printf("\n");
       }
    return 0;
}

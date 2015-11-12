bits 64
extern _puts
extern _sleep
global start

section .text
start:
    and     rsp, 0xFFFFFFFFFFFFFFF0 ; align the stack
    mov     rbp, rsp                ; setup rbp
    call    qword main              ; lets go to main!
    call    exit
    ret

main:
    push    rbp
    mov     rbp, rsp
    sub     rsp, 64
    lea     rdi, [rel qword hello]
    call    _puts
    mov     rdi, 8
    call    _sleep
    lea     rdi, [rel qword prompt1]
    call    _puts
    mov     rdi, rsp
    call    get_input
    mov     rsi, rax
    call    check1
    lea     rdi, [rel qword prompt2]
    call    _puts
    mov     rdi, rsp
    call    get_input
    mov     rdi, rsp
    call    check2
    lea     rdi, [rel qword prompt3]
    call    _puts
    mov     rdi, rsp
    call    get_input
    mov     rdi, rsp
    mov     rsi, rax
    call    check3
    lea     rdi, [rel qword win]
    call    _puts
    add     rsp, 64
    pop     rbp
    ret

check1:
    cmp     rsi, 16 ; check if the read in length was 15+1 newline
    jnz     fail
    ret

check2:
    mov     rcx, rax
    dec     rcx
    mov     rdx, rcx
    lea     rsi, [rel qword quest] ; answer in rsi, user input in rdi
inc:
    mov     rax, rsi
    add     rax, rdx
    sub     rax, rcx
    mov     rbx, rdi
    add     rbx, rdx
    sub     rbx, rcx
    mov     rax, [rax]
    mov     rbx, [rbx]
    inc     rbx
    cmp     al, bl
    jnz     fail
    dec     rcx
    cmp     rcx, 0
    jnz     inc
    ret

check3:
    push    rbp
    mov     rbp, rsp
    lea     rdx, [rsi*2]
    sub     rsp, rdx
    mov     rsi, rsp
    xor     rcx, rcx
    xchg    rsi, rdi
encoder:
    lodsb               ; load a byte of user input into al
    cmp     rax, 0xA
    jz      compare
    mov     r8, rcx
    and     r8, 0xAA
    xor     rax, r8
    stosb               ; store the encoded byte to our buffer
    add     rcx, rax
    and     rax, 0x69
    stosb               ; store the bogus byte into the buffer
    inc     rcx
    loop    encoder
compare:
    lea     rsi, [rel qword swallow]
    sub     rdi, swallow.len-1
    mov     rcx, swallow.len-2
    repz    cmpsb
    jnz     fail
    add     rsp, rdx
    pop     rbp
    ret

fail:
    lea     rdi, [rel qword no]
    call    _puts
exit:
    mov     rdi, 0                  ; return 0
    mov     rax, 0x2000001          ; exit syscall
    syscall

get_input:
    push    rbp
    mov     rbp, rsp
    sub     rsp, 8
    mov     [rsp], rdi
    mov     rax, 0x2000003  ; read syscall
    xor     rdi, rdi        ; stdin
    mov     rsi, [rsp]      ; buffer
    mov     rdx, 64         ; length to read
    syscall                 ; read dat user input
    add     rsp, 8
    pop     rbp
    ret

; data (section .data will not allow for multiple strings, this'll do
hello:      db "Hello there! Welcome to ",27,"[1;31mTHE BRIDGE OF DEATH!!",27,"[0m >:O",10,"All you have to do is answer five questions, NO THREE QUESTIONS, and then you can pass.",10,"If you get one wrong... well... you will be cast into the Gorge of Eternal Peril",10,"Good luck!! Oh, and they get harder as you go along.",0
prompt1:    db "What is your name?",0
prompt2:    db "What is your quest?",0
prompt3:    db "What is the ((flag?)) airspeed velcoity of an unladen swallow?",0
quest:      db "UpTfflUifIpmzHsbjm",0
win:        db "Okay, go ahead across the bridge (submit that flag) :)",0
no:         db "You died bcuz Eternal Peril :(",0

section .data
swallow:    db 82, 64, 65, 65, 177, 33, 45, 41, 109, 105, 196, 64, 246, 96, 209, 65, 5, 1, 27, 9, 184, 40, 55, 33, 26, 8, 0
.len        equ $-swallow


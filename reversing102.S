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
    mov     rdi, 3
;   call    _sleep
    lea     rdi, [rel qword prompt1]
    call    _puts
    mov     rdi, rsp
    call    get_input
    mov     rdi, rsp
    mov     rsi, rax
;   call    check1
    lea     rdi, [rel qword prompt2]
    call    _puts
    mov     rdi, rsp
    call    get_input
    mov     rdi, rsp
    mov     rsi, rax
;    call    check2
    lea     rdi, [rel qword prompt3]
    call    _puts
    mov     rdi, rsp
    call    get_input
    mov     rdi, rsp
    mov     rsi, rax
    call    check3
    mov     rdi, rsp
    call    _puts
    add     rsp, 64
    pop     rbp
    ret

check1:
    cmp     rsi, 16 ; check if the read in length was 15+1 newline
    jnz     exit
    ret

check2:
    mov     rcx, rsi
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
    jnz     exit
    dec     rcx
    cmp     rcx, 0
    jnz     inc
    ret

check3:
    xor eax, eax
    ret

exit:
    mov     rdi, 0                  ; return 0
    mov     rax, 0x2000001          ; exit syscall
    syscall                         ; run exit(<the return of main>)

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
hello:      db "Hello there! Welcome to reversing102, the premier reverse engineering challenge written in pure x86_64 assembly!",0
prompt1:    db "What is your name?",0
prompt2:    db "What is your quest?",0
prompt3:    db "What is the ((flag?)) airspeed velcoity of an unladen swallow?",0
quest:      db "UpTfflUifIpmzHsbjm",0
swallow:    db "bogus", 0

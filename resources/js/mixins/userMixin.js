export const userMixin ={
    methods:{
        fullName:function(name,nickname,surname){
            return name+"<span class='pink'> "+nickname+" </span>"+surname
        }
    }
}

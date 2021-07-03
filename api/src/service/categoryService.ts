import { Validator } from "alliance-rest/lib/validation/validator";
import { Page } from "alliance-rest/lib/pagination/page";
import { Pageable } from "alliance-rest/lib/pagination/pageable";
import { NotFoundError } from "alliance-rest/lib/error/notFoundError";

import { RoleRepository } from "../repository/categoryRepository";
import { IRole, Role } from "../models/auth/role";

export class RoleService {

    private static _roleRepository = RoleRepository;
    private static _validator = new Validator();

    /**
     * Create new role
     * @param role Role's data
     * @returns Promise of type Role
     */
    public static async createRole(role: IRole): Promise<Role> {
        let rolenameExists = await Role.findOne({ where: { rolename: role.rolename }});

        this._validator.text("rolename", role.rolename).required().alphaNum().minLen(3).maxLen(24).unique(() => !!rolenameExists).check();
        this._validator.number("hierarchy", role.hierarchy).required().min(0).max(1000).check();
        this._validator.throwErrors();

        return this._roleRepository.create(role);
    }

    /**
     * Update role
     * @param role Role's updated data
     * @returns Promise of type Role
     */
    public static async updateRole(id: string, updated: IRole): Promise<Role> {
        let role: Role = await this.findById(id);
        if(!role) throw new NotFoundError();

        let rolenameExists = await this.existsByRolename(updated.rolename); await Role.findOne({ where: { rolename: role.rolename }});

        this._validator.text("rolename", updated.rolename).alphaNum().minLen(3).maxLen(24).unique(() => !!rolenameExists).check()
        this._validator.number("hierarchy", updated.hierarchy).min(0).max(1000).check();

        if(!updated.permissions) {
            updated.permissions = role.permissions;
        }
        
        this._validator.throwErrors();
        return this._roleRepository.update(id, role);
    }

    /**
     * Delete role by its id
     * @param id Id of role
     */
    public static async deleteMemberById(id: string): Promise<void> {
        this._roleRepository.deleteById(id);
    }

    /**
     * Get a list of roles
     * @param pageable Page settings
     */
    public static async listAll(pageable: Pageable): Promise<Page<Role>> {
        return this._roleRepository.listAll(pageable);
    }

    /**
     * Find a role by its id
     * @param id Role's id
     * @returns Promise of type Role
     */
    public static async findById(id: string): Promise<Role> {
        return this._roleRepository.findById(id);
    }

    /**
     * Find role by its rolename
     * @param rolename Role's name
     * @returns Promise of type Role
     */
    public static async findByRolename(username: string): Promise<Role> {
        return this._roleRepository.findByRolename(username);
    }

    /**
     * Find role by its rolename and create new entry if it does not exist
     * @param rolename Role's rolename
     * @param create Data to be created
     * @returns Promise of type Role
     */
    public static async findByRolenameOrCreate(rolename: string, create: IRole): Promise<Role> {
        return this._roleRepository.findByRolenameOrCreate(rolename, create);
    }

    /**
     * Check if a role with the rolename already exists
     * @param rolename Rolename to check
     * @returns Promise of type boolean
     */
     public static async existsByRolename(rolename: string): Promise<boolean> {
        return this._roleRepository.existsByRolename(rolename);
    }

}